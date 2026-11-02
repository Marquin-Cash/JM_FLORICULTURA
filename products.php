<?php
require_once __DIR__.'/../../app/bootstrap.php';
$method=$_SERVER['REQUEST_METHOD'] ?? 'GET'; $id=isset($_GET['id'])?(int)$_GET['id']:0;
if ($method==='GET') {
  $sql='SELECT id,name,category,description,price,stock,image_url,active FROM products'; $params=[];
  if (!current_user() || (current_user()['role']??'')!=='admin') { $sql.=' WHERE active=1'; }
  if ($id>0) { $sql.=(str_contains($sql,' WHERE ')?' AND ':' WHERE ').'id=?'; $params[]=$id; }
  $sql.=' ORDER BY name'; $s=db()->prepare($sql); $s->execute($params); json_response(['products'=>$s->fetchAll()]);
}
require_method('POST','PUT','DELETE'); $u=require_admin(); require_csrf(); same_origin_guard(); $pdo=db();
if ($method==='DELETE') { if($id<1) json_response(['error'=>'ID inválido.'],422); $s=$pdo->prepare('DELETE FROM products WHERE id=?');$s->execute([$id]);audit('product.delete',$u['id'],['id'=>$id]);json_response(['ok'=>true]); }
$in=request_json(); $name=clean_string($in['name']??'',120);$category=clean_string($in['category']??'',80);$description=clean_string($in['description']??'',2000);$image=clean_string($in['image_url']??'',1000);$price=$in['price']??null;$stock=$in['stock']??null;$active=isset($in['active']) ? (in_array($in['active'],[1,'1',true],true)?1:(in_array($in['active'],[0,'0',false],true)?0:-1)) : 1;
if($name===''||$category===''||!is_numeric($price)||(float)$price<0||(float)$price>1000000||filter_var($stock,FILTER_VALIDATE_INT)===false||(int)$stock<0||(int)$stock>1000000)json_response(['error'=>'Dados do produto inválidos.'],422);
if($active<0)json_response(['error'=>'Estado do produto inválido.'],422); $isHttpImage=filter_var($image,FILTER_VALIDATE_URL) && in_array(strtolower((string)parse_url($image,PHP_URL_SCHEME)),['http','https'],true); $isLocalImage=preg_match('#^/[A-Za-z0-9/_\-.%?=&]+$#D',$image)===1; if($image!=='' && !$isHttpImage && !$isLocalImage)json_response(['error'=>'Imagem deve usar URL HTTP(S) válida ou caminho local seguro, sem HTML ou javascript:.'],422);
if($method==='POST'){$s=$pdo->prepare('INSERT INTO products(name,category,description,price,stock,image_url,active,created_at,updated_at) VALUES(?,?,?,?,?,?,?,?,?) RETURNING id');$s->execute([$name,$category,$description,(float)$price,(int)$stock,$image?:null,$active,date('Y-m-d H:i:s'),date('Y-m-d H:i:s')]);$id=(int)$s->fetchColumn();audit('product.create',$u['id'],['id'=>$id]);json_response(['ok'=>true,'id'=>$id],201);}
if($id<1)json_response(['error'=>'ID inválido.'],422);$s=$pdo->prepare('UPDATE products SET name=?,category=?,description=?,price=?,stock=?,image_url=?,active=?,updated_at=? WHERE id=?');$s->execute([$name,$category,$description,(float)$price,(int)$stock,$image?:null,$active,date('Y-m-d H:i:s'),$id]);audit('product.update',$u['id'],['id'=>$id]);json_response(['ok'=>true]);

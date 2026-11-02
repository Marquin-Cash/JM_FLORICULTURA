<?php
declare(strict_types=1);
require_once __DIR__.'/../../app/bootstrap.php';
$method=$_SERVER['REQUEST_METHOD']??'GET';
if($method==='GET'){
  $u=require_admin(); $pdo=db();
  $s=$pdo->query('SELECT id,customer_name,customer_phone,delivery_address,delivery_date,total,payment_method,payment_status,delivery_status,created_at,note,user_id,pricing_mode,subtotal,shipping_amount FROM orders ORDER BY created_at DESC LIMIT 500');
  $orders=$s->fetchAll();
  $items=$pdo->query('SELECT order_id,product_id,product_name,quantity,unit_price FROM order_items ORDER BY id')->fetchAll();
  $by=[]; foreach($items as $item){$oid=(int)$item['order_id'];$by[$oid][]=$item;} foreach($orders as &$o){$o['items']=$by[(int)$o['id']]??[];} unset($o);
  json_response(['orders'=>$orders]);
}
require_method('POST','PATCH'); same_origin_guard(); require_csrf(); $pdo=db();
if($method==='PATCH'){
  $u=require_admin(); $in=request_json(); $id=filter_var($in['id']??null,FILTER_VALIDATE_INT); $field=$in['field']??''; $value=$in['value']??null;
  if(!$id||!in_array($field,['payment_status','delivery_status'],true)||!in_array($value,$field==='payment_status'?['pending','paid','refunded']:['open','delivered','cancelled'],true))json_response(['error'=>'Atualização inválida.'],422);
  try { $pdo->beginTransaction(); $q=$pdo->prepare('SELECT delivery_status FROM orders WHERE id=? FOR UPDATE');$q->execute([$id]);$old=$q->fetch();if(!$old){$pdo->rollBack();json_response(['error'=>'Pedido não encontrado.'],404);}
    if($field==='delivery_status' && $value==='cancelled' && $old['delivery_status']==='delivered'){$pdo->rollBack();json_response(['error'=>'Um pedido já entregue não pode ser cancelado automaticamente.'],409);}
    if($field==='delivery_status' && $value==='cancelled' && $old['delivery_status']!=='cancelled'){
      $it=$pdo->prepare('SELECT product_id,quantity FROM order_items WHERE order_id=?');$it->execute([$id]);$restore=$pdo->prepare('UPDATE products SET stock=stock+?,updated_at=NOW() WHERE id=?');foreach($it->fetchAll() as $row){if($row['product_id']!==null)$restore->execute([(int)$row['quantity'],(int)$row['product_id']]);}
    }
    if($field==='delivery_status' && $old['delivery_status']==='cancelled' && $value!=='cancelled'){$pdo->rollBack();json_response(['error'=>'Pedido cancelado não pode ser reaberto automaticamente. Crie um novo pedido.'],409);}
    $sql="UPDATE orders SET {$field}=?,updated_at=NOW() WHERE id=?";$pdo->prepare($sql)->execute([$value,$id]);$pdo->commit();audit('order.status_update',(int)$u['id'],['id'=>$id,'field'=>$field,'value'=>$value]);json_response(['ok'=>true]);
  } catch(Throwable $e){if($pdo->inTransaction())$pdo->rollBack();error_log('Order status update failed: '.$e->getMessage());json_response(['error'=>'Não foi possível atualizar o pedido.'],500);}
}
rate_limit('orders',(string)($_SERVER['REMOTE_ADDR']??'unknown'),10,3600);
$in=request_json(); $name=clean_string($in['customer_name']??'',120);$rawPhone=$in['customer_phone']??'';if(!is_string($rawPhone)&&!is_int($rawPhone))json_response(['error'=>'Telefone inválido.'],422);$phone=preg_replace('/\D/','',(string)$rawPhone);$address=clean_string($in['delivery_address']??'',500);$date=$in['delivery_date']??'';$payment=$in['payment_method']??'';$items=$in['items']??null; $note=clean_string($in['note']??'',2000);
if(!is_string($date)||!preg_match('/^\d{4}-\d{2}-\d{2}$/',$date)||!checkdate((int)substr($date,5,2),(int)substr($date,8,2),(int)substr($date,0,4))||$name===''||strlen($phone)<10||strlen($phone)>13||$address===''||!is_string($date)||!preg_match('/^\d{4}-\d{2}-\d{2}$/',$date)||$date<date('Y-m-d')||!in_array($payment,['Pix','Cartão de crédito','Cartão de débito','Dinheiro na entrega'],true)||!is_array($items)||count($items)<1||count($items)>50)json_response(['error'=>'Dados do pedido inválidos.'],422);
try{
  $pdo->beginTransaction();$total=0.0;$normalized=[];$quantities=[];
  foreach($items as $item){if(!is_array($item))throw new DomainException('Item do pedido inválido.');$pid=filter_var($item['product_id']??null,FILTER_VALIDATE_INT);$qty=filter_var($item['quantity']??null,FILTER_VALIDATE_INT);if(!$pid||!$qty||$qty<1||$qty>100)throw new DomainException('Item do pedido inválido.');$quantities[$pid]=($quantities[$pid]??0)+$qty;if($quantities[$pid]>100)throw new DomainException('Quantidade total inválida.');}
  $sel=$pdo->prepare('SELECT id,name,price,stock,active FROM products WHERE id=? FOR UPDATE');$dec=$pdo->prepare('UPDATE products SET stock=stock-?,updated_at=NOW() WHERE id=?');
  ksort($quantities); foreach($quantities as $pid=>$qty){$sel->execute([$pid]);$p=$sel->fetch();if(!$p||(int)$p['active']!==1)throw new DomainException('Produto indisponível.');if((int)$p['stock']<$qty)throw new DomainException('Estoque insuficiente para '.$p['name'].'.');$unit=(float)$p['price'];$total+=$unit*$qty;$normalized[]=['id'=>(int)$pid,'name'=>$p['name'],'price'=>$unit,'quantity'=>$qty];$dec->execute([$qty,$pid]);}
  if(!is_finite($total)||$total>999999999)throw new DomainException('Valor total inválido.');
  // O vendedor define o frete pelo WhatsApp; NULL significa ainda não informado.
  $subtotal=$total;
  $st=$pdo->prepare('INSERT INTO orders(pricing_mode,subtotal,shipping_amount,user_id,note,customer_name,customer_phone,delivery_address,delivery_date,total,payment_method,payment_status,delivery_status,created_at,updated_at) VALUES(\'quote_pending\',?,NULL,?,?,?,?,?,?,?,?,\'pending\',\'open\',NOW(),NOW()) RETURNING id');$st->execute([round($subtotal,2),current_user()['id']??null,$note,$name,$phone,$address,$date,round($total,2),$payment]);$orderId=(int)$st->fetchColumn();
  $it=$pdo->prepare('INSERT INTO order_items(order_id,product_id,product_name,quantity,unit_price) VALUES(?,?,?,?,?)');foreach($normalized as $x)$it->execute([$orderId,$x['id'],$x['name'],$x['quantity'],$x['price']]);
  $pdo->commit();audit('order.create',current_user()['id']??null,['order_id'=>$orderId]);json_response(['ok'=>true,'order_id'=>$orderId,'subtotal'=>round($subtotal,2),'shipping'=>null,'total'=>null,'pricing_mode'=>'quote_pending'],201);
}catch(DomainException $e){if($pdo->inTransaction())$pdo->rollBack();json_response(['error'=>$e->getMessage()],422);}catch(Throwable $e){if($pdo->inTransaction())$pdo->rollBack();error_log('Order create failed: '.$e->getMessage());json_response(['error'=>'Não foi possível registrar o pedido.'],500);}

<?php
declare(strict_types=1);
require_once __DIR__.'/../../app/bootstrap.php';
$action=$_GET['action']??'me';
if ($action==='me') {
    require_method('GET');
    json_response(['user'=>current_user(),'csrf'=>csrf_token()]);
}
require_method('POST');
same_origin_guard();
require_csrf();
$in=request_json();
$ip=(string)($_SERVER['REMOTE_ADDR']??'unknown');
if ($action==='register') {
    rate_limit('register',$ip,10,3600);
    $name=clean_string($in['name']??'',120);
    $email=strtolower(clean_string($in['email']??'',254));
    $phone=preg_replace('/\D/','',clean_string($in['phone']??'',30));
    $password=$in['password']??'';
    if ($name==='' || !filter_var($email,FILTER_VALIDATE_EMAIL) || strlen($phone)<10 || strlen($phone)>13
        || !is_string($password) || strlen($password)<8 || strlen($password)>72) {
        json_response(['error'=>'Informe nome, e-mail, telefone com DDD e senha de 8 a 72 bytes.'],422);
    }
    try {
        $q=db()->prepare("INSERT INTO users(email,display_name,phone,password_hash,role) VALUES(?,?,?,?,'customer') RETURNING *");
        $q->execute([$email,$name,$phone,password_hash($password,PASSWORD_DEFAULT)]);
        $u=$q->fetch();
    } catch (PDOException $e) {
        if ($e->getCode()==='23505') json_response(['error'=>'Este e-mail já está cadastrado. Entre na sua conta.'],409);
        throw $e;
    }
    login_session($u); audit('auth.register',(int)$u['id']);
    json_response(['user'=>current_user(),'csrf'=>csrf_token()],201);
}
if ($action==='login') {
    $email=strtolower(clean_string($in['email']??'',254));
    $password=$in['password']??'';
    if (($email!=='admin' && !filter_var($email,FILTER_VALIDATE_EMAIL)) || !is_string($password) || $password==='' || strlen($password)>72)
        json_response(['error'=>'E-mail ou senha inválidos.'],422);
    rate_limit('login',$email.'|'.$ip,8,900);
    rate_limit('login-ip',$ip,100,900);
    $q=db()->prepare('SELECT * FROM users WHERE email=? AND is_active=1'); $q->execute([$email]); $u=$q->fetch();
    if (!$u || !password_verify($password,$u['password_hash'])) {
        audit('auth.login_failed',null,['email_hash'=>hash('sha256',$email)]);
        json_response(['error'=>'E-mail ou senha inválidos.'],401);
    }
    login_session($u); audit('auth.login_success',(int)$u['id']);
    json_response(['user'=>current_user(),'csrf'=>csrf_token()]);
}
if ($action==='profile') {
    $session=require_auth();
    $name=clean_string($in['name']??'',120);
    $email=strtolower(clean_string($in['email']??'',254));
    $phone=preg_replace('/\D/','',clean_string($in['phone']??'',30));
    $old=$in['current_password']??''; $new=$in['password']??'';
    if ($name==='' || (!filter_var($email,FILTER_VALIDATE_EMAIL) && !($session['role']==='admin' && $email==='admin')) || (strlen($phone)<10 && !($session['role']==='admin' && $phone==='')) || strlen($phone)>13
        || !is_string($old) || !is_string($new) || ($new!=='' && (strlen($new)<8 || strlen($new)>72)))
        json_response(['error'=>'Confira nome, e-mail, telefone e senha (8 a 72 bytes).'],422);
    $q=db()->prepare('SELECT * FROM users WHERE id=? AND is_active=1'); $q->execute([$session['id']]); $u=$q->fetch();
    if (!$u) json_response(['error'=>'Conta indisponível.'],401);
    if ($email!==$u['email'] || $new!=='') {
        rate_limit('profile',(string)$u['id'],8,900);
        if (!password_verify($old,$u['password_hash'])) json_response(['error'=>'Informe a senha atual correta.'],403);
    }
    try {
        $q=db()->prepare('UPDATE users SET display_name=?,email=?,phone=?,password_hash=?,updated_at=NOW() WHERE id=? RETURNING *');
        $q->execute([$name,$email,$phone,$new!==''?password_hash($new,PASSWORD_DEFAULT):$u['password_hash'],$u['id']]);
        login_session($q->fetch());
    } catch (PDOException $e) {
        if ($e->getCode()==='23505') json_response(['error'=>'E-mail já cadastrado.'],409);
        throw $e;
    }
    json_response(['user'=>current_user(),'csrf'=>csrf_token()]);
}
if ($action==='logout') {
    require_auth(); $_SESSION=[]; session_regenerate_id(true);
    json_response(['ok'=>true,'csrf'=>csrf_token()]);
}
json_response(['error'=>'Ação desconhecida.'],404);

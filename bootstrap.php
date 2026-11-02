<?php
declare(strict_types=1);

// Configure AP_DB_* no ambiente do PHP/Apache. Nunca guarde credenciais no diretório público.
require_once __DIR__ . '/../config/db.php';
date_default_timezone_set('America/Sao_Paulo');
define('APP_ORIGIN', (string) app_config()['origin']); // Em produção, defina a origem exata, ex.: https://floricultura.exemplo.com
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
ini_set('session.use_strict_mode', '1');
ini_set('session.use_only_cookies', '1');
ini_set('session.use_trans_sid', '0');
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Strict');
session_name('APSESSID');
session_set_cookie_params(['lifetime'=>0,'path'=>'/','secure'=>$isHttps,'httponly'=>true,'samesite'=>'Strict']);
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: no-referrer');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
header('Cache-Control: no-store, private');
header('Cross-Origin-Resource-Policy: same-origin');
header('Content-Security-Policy: default-src \'none\'; frame-ancestors \'none\'; base-uri \'none\'; form-action \'self\'');
if ($isHttps) header('Strict-Transport-Security: max-age=31536000; includeSubDomains');

function json_response(array $data, int $status=200): never {
    http_response_code($status); header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES); exit;
}
function request_json(): array {
    $length=(int)($_SERVER['CONTENT_LENGTH'] ?? 0);
    if ($length > 65536) json_response(['error'=>'Requisição muito grande.'],413);
    $type=strtolower(trim(explode(';', $_SERVER['CONTENT_TYPE'] ?? '')[0]));
    if ($type !== 'application/json') json_response(['error'=>'Envie os dados como application/json.'],415);
    $raw=file_get_contents('php://input', false, null, 0, 65537);
    if ($raw === false || strlen($raw)>65536) json_response(['error'=>'Requisição muito grande.'],413);
    $v=json_decode($raw ?: '[]', true);
    if (!is_array($v) || json_last_error() !== JSON_ERROR_NONE) json_response(['error'=>'JSON inválido.'],400);
    return $v;
}
function db(): PDO
{
    try { return connection(); }
    catch (Throwable $e) {
        error_log('PostgreSQL connection failure: ' . $e->getMessage());
        json_response(['error'=>'Não foi possível conectar ao PostgreSQL. Confira config/local.php.'],503);
    }
}
function require_method(string ...$methods): void { if (!in_array($_SERVER['REQUEST_METHOD'] ?? 'GET',$methods,true)) { header('Allow: '.implode(', ',$methods)); json_response(['error'=>'Método não permitido.'],405); } }
function csrf_token(): string { if (empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(32)); return $_SESSION['csrf']; }
function require_csrf(): void { $got=$_SERVER['HTTP_X_CSRF_TOKEN'] ?? ''; if (!is_string($got) || empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'],$got)) json_response(['error'=>'Token CSRF inválido ou expirado. Atualize a página.'],419); }
function current_user(): ?array {
    if (empty($_SESSION['user']['id'])) return null;
    $q=db()->prepare('SELECT * FROM users WHERE id=? AND is_active=1');
    $q->execute([$_SESSION['user']['id']]); $u=$q->fetch();
    if (!$u) { unset($_SESSION['user']); return null; }
    return $_SESSION['user']=public_user($u);
}
function require_auth(): array { $u=current_user(); if (!$u) json_response(['error'=>'Autenticação necessária.'],401); return $u; }
function require_admin(): array { $u=require_auth(); if (($u['role'] ?? '') !== 'admin') json_response(['error'=>'Acesso negado.'],403); return $u; }
function audit(string $event, ?int $userId=null, array $meta=[]): void {
    try { $s=db()->prepare('INSERT INTO audit_logs(user_id,event,ip_address,details,created_at) VALUES(?,?,?,?,NOW())'); $s->execute([$userId,$event,substr((string)($_SERVER['REMOTE_ADDR']??''),0,45),json_encode($meta,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)]); } catch(Throwable $e) { error_log('AP audit failure: '.$e->getMessage()); }
}
function clean_string(mixed $v, int $max=255): string { if (!is_string($v)) json_response(['error'=>'Campo inválido.'],422); $v=trim($v); if (mb_strlen($v)>$max) json_response(['error'=>'Campo excede o tamanho permitido.'],422); return $v; }
function same_origin_guard(): void {
    $origin=(string)($_SERVER['HTTP_ORIGIN'] ?? '');
    if ($origin === '' && isset($_SERVER['HTTP_REFERER'])) {
        $parts=parse_url((string)$_SERVER['HTTP_REFERER']);
        if (is_array($parts) && isset($parts['scheme'],$parts['host'])) $origin=$parts['scheme'].'://'.$parts['host'].(isset($parts['port'])?':'.$parts['port']:'');
    }
    $expected=APP_ORIGIN !== '' ? APP_ORIGIN : (((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')?'https':'http').'://'.($_SERVER['HTTP_HOST'] ?? ''));
    if ($origin === '' || !hash_equals(rtrim($expected,'/'),rtrim($origin,'/'))) json_response(['error'=>'Origem não permitida.'],403);
}

// Falhas não devem expor consultas ou credenciais ao navegador.
set_exception_handler(function (Throwable $e): void {
    error_log('JM API: ' . $e->getMessage());
    if ($e instanceof PDOException && in_array((string)$e->getCode(),['42P01','42703','3F000'],true)) {
        json_response(['error'=>'A estrutura do banco precisa ser preparada/atualizada. Execute bin/atualizar.php pelo PHP do XAMPP, conforme ATUALIZACAO.md.'],503);
    }
    json_response(['error'=>'Não foi possível concluir a operação. Verifique a instalação do banco e o log do PHP.'],500);
});
function public_user(array $u): array {
    return ['id'=>(int)$u['id'],'email'=>$u['email'],'name'=>$u['display_name'],
            'phone'=>$u['phone']??'','role'=>$u['role']];
}
function login_session(array $u): void {
    session_regenerate_id(true);
    $_SESSION['user']=public_user($u);
    $_SESSION['csrf']=bin2hex(random_bytes(32));
}
function rate_limit(string $scope, string $identity, int $limit, int $seconds): void {
    $key=hash('sha256',$scope.'|'.$identity);
    $sql="INSERT INTO rate_limits(attempt_key,attempts,window_started) VALUES(?,1,NOW())
          ON CONFLICT(attempt_key) DO UPDATE SET
          attempts=CASE WHEN rate_limits.window_started < NOW() - CAST(? AS INTEGER)*INTERVAL '1 second'
                        THEN 1 ELSE rate_limits.attempts+1 END,
          window_started=CASE WHEN rate_limits.window_started < NOW() - CAST(? AS INTEGER)*INTERVAL '1 second'
                              THEN NOW() ELSE rate_limits.window_started END RETURNING attempts";
    $q=db()->prepare($sql); $q->execute([$key,$seconds,$seconds]);
    if ((int)$q->fetchColumn()>$limit) {
        header('Retry-After: '.$seconds);
        json_response(['error'=>'Muitas tentativas. Aguarde alguns minutos e tente novamente.'],429);
    }
}

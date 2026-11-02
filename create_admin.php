<?php
declare(strict_types=1);
if (PHP_SAPI!=='cli') { http_response_code(404); exit; }
require_once __DIR__.'/../config/db.php';
$email=strtolower(trim($argv[1]??'')); $displayName=trim($argv[2]??'Administrador');
if (!filter_var($email,FILTER_VALIDATE_EMAIL) || $displayName==='' || mb_strlen($displayName)>120) {
    fwrite(STDERR,"Uso: php bin/create_admin.php seu@email.com \"Nome do administrador\"\n"); exit(1);
}
fwrite(STDOUT,"Senha de acesso do administrador (14 a 72 bytes; entrada visível neste terminal): ");
$password=rtrim((string)fgets(STDIN),"\r\n");
if (strlen($password)<14 || strlen($password)>72) { fwrite(STDERR,"Senha fora do limite.\n"); exit(1); }
try {
    $pdo=connection();
    $q=$pdo->prepare("INSERT INTO users(email,display_name,password_hash,role) VALUES(?,?,?,'admin')
        ON CONFLICT(email) DO UPDATE SET display_name=EXCLUDED.display_name,password_hash=EXCLUDED.password_hash,
        role='admin',is_active=1,updated_at=NOW()");
    $q->execute([$email,$displayName,password_hash($password,PASSWORD_DEFAULT)]);
    fwrite(STDOUT,"Administrador criado/atualizado. Entre em /admin.php.\n");
} catch (Throwable $e) { fwrite(STDERR,"Falha: ".$e->getMessage()."\n"); exit(1); }

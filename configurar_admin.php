<?php
declare(strict_types=1);
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require_once __DIR__.'/../config/db.php';
try {
    $pdo=connection();
    $q=$pdo->prepare("INSERT INTO jm_app.users(email,display_name,password_hash,role) VALUES('admin','Administrador',?,'admin')
        ON CONFLICT(email) DO UPDATE SET password_hash=EXCLUDED.password_hash,role='admin',is_active=1,updated_at=NOW()");
    $q->execute([password_hash('admin 123',PASSWORD_DEFAULT)]);
    echo "Usuário admin configurado com a senha solicitada.\n";
} catch (Throwable $e) { fwrite(STDERR,$e->getMessage().PHP_EOL); exit(1); }

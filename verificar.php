<?php
declare(strict_types=1);
if (PHP_SAPI!=='cli') { http_response_code(404); exit; }
echo 'PHP: '.PHP_VERSION.PHP_EOL;
if (PHP_VERSION_ID<80100) { fwrite(STDERR,"Use PHP 8.1 ou superior.\n"); exit(1); }
foreach (['pdo_pgsql','mbstring'] as $ext) {
    if (!extension_loaded($ext)) { fwrite(STDERR,"Habilite a extensão $ext no php.ini.\n"); exit(1); }
}
require_once __DIR__.'/../config/db.php';
try {
    $pdo=connection();
    echo 'Banco conectado: '.$pdo->query('SELECT current_database()')->fetchColumn().PHP_EOL;
    foreach (['users','products','orders','order_items','audit_logs','rate_limits'] as $table) {
        $q=$pdo->prepare('SELECT to_regclass(?)');$q->execute(['jm_app.'.$table]);
        if (!$q->fetchColumn()) throw new RuntimeException('Falta jm_app.'.$table.'. Execute php bin/preparar_banco.php.');
    }
    echo 'Produtos: '.$pdo->query('SELECT COUNT(*) FROM jm_app.products')->fetchColumn().PHP_EOL;
    echo 'Administradores ativos: '.$pdo->query("SELECT COUNT(*) FROM jm_app.users WHERE role='admin' AND is_active=1")->fetchColumn().PHP_EOL;
    echo "Estrutura e conexão verificadas. Teste cadastro, login e pedido pelo navegador.\n";
} catch (Throwable $e) { fwrite(STDERR,$e->getMessage().PHP_EOL); exit(1); }

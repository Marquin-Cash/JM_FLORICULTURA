<?php
declare(strict_types=1);
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
foreach (['pdo_pgsql','mbstring'] as $extension) {
    if (!extension_loaded($extension)) {
        fwrite(STDERR,"Habilite extension=$extension no php.ini do XAMPP e reinicie o Apache.\n"); exit(1);
    }
}
$argv[]='--catalogo-se-vazio';
require __DIR__.'/preparar_banco.php';
require __DIR__.'/configurar_admin.php';
require __DIR__.'/verificar.php';

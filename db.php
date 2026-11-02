<?php
declare(strict_types=1);
function app_config(): array
{
    static $config = null;
    if ($config !== null) return $config;
    $config = require __DIR__ . '/local.php';
    foreach (['host'=>'AP_DB_HOST','port'=>'AP_DB_PORT','database'=>'AP_DB_NAME',
              'username'=>'AP_DB_USER','password'=>'AP_DB_PASS','origin'=>'AP_ORIGIN'] as $key=>$env) {
        $value = getenv($env);
        if ($value !== false && $value !== '') $config[$key] = $value;
    }
    return $config;
}
function connection(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;
    $c = app_config();
    $pdo = new PDO(
        "pgsql:host={$c['host']};port={$c['port']};dbname={$c['database']}",
        $c['username'], $c['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
    $pdo->exec("SET search_path TO jm_app, public");
    $pdo->exec("SET TIME ZONE 'America/Sao_Paulo'");
    return $pdo;
}

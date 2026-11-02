<?php
declare(strict_types=1);
if (PHP_SAPI!=='cli') { http_response_code(404); exit; }
require_once __DIR__.'/../config/db.php';
try {
    $pdo=connection(); $pdo->beginTransaction();
    $pdo->exec(<<<'DDL'
CREATE SCHEMA IF NOT EXISTS jm_app;
CREATE TABLE IF NOT EXISTS jm_app.users (
 id BIGSERIAL PRIMARY KEY, email VARCHAR(254) NOT NULL UNIQUE, display_name VARCHAR(120) NOT NULL,
 phone VARCHAR(13) NOT NULL DEFAULT '', password_hash VARCHAR(255) NOT NULL,
 role VARCHAR(20) NOT NULL DEFAULT 'customer' CHECK(role IN ('admin','customer')),
 is_active SMALLINT NOT NULL DEFAULT 1 CHECK(is_active IN (0,1)),
 created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);
CREATE TABLE IF NOT EXISTS jm_app.rate_limits (
 attempt_key CHAR(64) PRIMARY KEY,attempts INTEGER NOT NULL DEFAULT 0,window_started TIMESTAMPTZ NOT NULL
);
CREATE TABLE IF NOT EXISTS jm_app.products (
 id BIGSERIAL PRIMARY KEY,legacy_id BIGINT UNIQUE, seed_id BIGINT UNIQUE,
 name VARCHAR(120) NOT NULL,category VARCHAR(80) NOT NULL,description TEXT NOT NULL DEFAULT '',
 price NUMERIC(10,2) NOT NULL CHECK(price>=0),stock INTEGER NOT NULL DEFAULT 0 CHECK(stock>=0),
 image_url VARCHAR(1000), active SMALLINT NOT NULL DEFAULT 1 CHECK(active IN (0,1)),
 created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);
CREATE TABLE IF NOT EXISTS jm_app.orders (
 id BIGSERIAL PRIMARY KEY,user_id BIGINT REFERENCES jm_app.users(id) ON DELETE SET NULL,
 note TEXT NOT NULL DEFAULT '', customer_name VARCHAR(120) NOT NULL,customer_phone VARCHAR(13) NOT NULL,
 delivery_address VARCHAR(500) NOT NULL,delivery_date DATE NOT NULL,total NUMERIC(12,2) NOT NULL CHECK(total>=0),
 payment_method VARCHAR(30) NOT NULL CHECK(payment_method IN ('Pix','Cartão de crédito','Cartão de débito','Dinheiro na entrega')),
 payment_status VARCHAR(20) NOT NULL DEFAULT 'pending' CHECK(payment_status IN ('pending','paid','refunded')),
 delivery_status VARCHAR(20) NOT NULL DEFAULT 'open' CHECK(delivery_status IN ('open','delivered','cancelled')),
 created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(), updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);
CREATE TABLE IF NOT EXISTS jm_app.order_items (
 id BIGSERIAL PRIMARY KEY,order_id BIGINT NOT NULL REFERENCES jm_app.orders(id) ON DELETE CASCADE,
 product_id BIGINT REFERENCES jm_app.products(id) ON DELETE SET NULL,product_name VARCHAR(120) NOT NULL,
 quantity INTEGER NOT NULL CHECK(quantity>0),unit_price NUMERIC(10,2) NOT NULL CHECK(unit_price>=0)
);
CREATE TABLE IF NOT EXISTS jm_app.audit_logs (
 id BIGSERIAL PRIMARY KEY,user_id BIGINT REFERENCES jm_app.users(id) ON DELETE SET NULL,event VARCHAR(80) NOT NULL,
 ip_address VARCHAR(45),details JSONB,created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);
CREATE INDEX IF NOT EXISTS idx_products_active ON jm_app.products(active,category);
CREATE INDEX IF NOT EXISTS idx_orders_created ON jm_app.orders(created_at);
CREATE INDEX IF NOT EXISTS idx_order_items_order ON jm_app.order_items(order_id);
DDL);
    // Migração aditiva: pedidos antigos mantêm seus totais originais.
    $pdo->exec("ALTER TABLE jm_app.orders ADD COLUMN IF NOT EXISTS pricing_mode VARCHAR(30) NOT NULL DEFAULT 'legacy_total'");
    $pdo->exec('ALTER TABLE jm_app.orders ADD COLUMN IF NOT EXISTS subtotal NUMERIC(12,2)');
    $pdo->exec('ALTER TABLE jm_app.orders ADD COLUMN IF NOT EXISTS shipping_amount NUMERIC(10,2)');
    // Nunca altera as tabelas public.produtos/public.categorias nem sobrescreve importações anteriores.
    $count=0; $categories=[];
    if ($pdo->query("SELECT to_regclass('public.categorias')")->fetchColumn()) {
        foreach ($pdo->query('SELECT * FROM public.categorias') as $c) $categories[$c['id']]=$c['nome']??'Presentes';
    }
    $insert=$pdo->prepare('INSERT INTO jm_app.products(legacy_id,name,category,description,price,stock,image_url,active)
                          VALUES(?,?,?,?,?,?,?,?) ON CONFLICT(legacy_id) DO NOTHING');
    if ($pdo->query("SELECT to_regclass('public.produtos')")->fetchColumn()) {
        foreach ($pdo->query('SELECT * FROM public.produtos ORDER BY id') as $p) {
            if (!isset($p['id'],$p['nome'],$p['preco'])) throw new RuntimeException('produtos precisa das colunas id, nome e preco.');
            $image=(string)($p['imagem']??'');
            if ($image!=='' && !preg_match('#^https?://#i',$image)) {
                $base=rawurldecode(basename(str_replace('\\','/',$image)));
                if (!is_file(__DIR__.'/../public/assets/images/produtos/'.$base)) {
                    fwrite(STDERR,'Aviso: imagem ausente no produto '.$p['id'].': '.$base.'. O cadastro será importado sem foto.'.PHP_EOL);
                    $base='';
                }
                $image=$base!==''?'/assets/images/produtos/'.rawurlencode($base):'';
            }
            $active=in_array($p['ativo']??true,[false,0,'0','f','false'],true)?0:1;
            $category=$categories[$p['categoria_id']??'']??($p['categoria']??'Presentes');
            $insert->execute([$p['id'],$p['nome'],$category,$p['descricao']??'',$p['preco'],
                             max(0,(int)($p['estoque']??0)),$image,$active]);
            $count+=$insert->rowCount();
        }
    }
    // Catálogo do novo ZIP é opcional: não mistura preços novos com os seus automaticamente.
    if (in_array('--catalogo-novo',$argv,true) || (in_array('--catalogo-se-vazio',$argv,true) && (int)$pdo->query('SELECT COUNT(*) FROM jm_app.products')->fetchColumn()===0)) {
        if ((int)$pdo->query('SELECT COUNT(*) FROM jm_app.products')->fetchColumn()>0)
            throw new RuntimeException('Catálogo já contém produtos. Importação opcional recusada para evitar duplicações.');
        $seed=json_decode(file_get_contents(__DIR__.'/../app/catalogo_novo.json'),true,512,JSON_THROW_ON_ERROR);
        $q=$pdo->prepare('INSERT INTO jm_app.products(seed_id,name,category,description,price,stock,image_url) VALUES(?,?,?,?,?,0,?) ON CONFLICT(seed_id) DO NOTHING');
        foreach ($seed as $p) { $q->execute([$p['id'],$p['n'],$p['c'],$p['d'],$p['p'],$p['i']]); $count+=$q->rowCount(); }
    }
    $pdo->commit();
    echo "Preparação concluída. Produtos importados nesta execução: $count.\n";
    echo "Confira jm_app.products no pgAdmin. Produtos sem estoque antigo começam com 0.\n";
} catch (Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
    fwrite(STDERR,"Preparação cancelada sem alterações parciais: ".$e->getMessage()."\n"); exit(1);
}

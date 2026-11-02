<?php
require_once __DIR__.'/../config/db.php';
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Cache-Control: no-store');
?>
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin seguro — J&M Floricultura</title>
<link rel="stylesheet" href="css/admin.css">
<script src="js/admin.js?v=20261019-2" defer></script>
</head>
<body>
<header><div><strong>J&M Floricultura</strong><span>Administração segura</span></div><a href="index.php">Voltar à loja</a></header>
<main>
<section id="login-card" class="card login-card">
<h1>Acesso administrativo</h1><p>Entre com seu usuário ou e-mail administrativo.</p>
<form id="login-form"><label>Usuário ou e-mail<input id="email" type="text" autocomplete="username" required maxlength="254"></label><label>Senha<input id="password" type="password" autocomplete="current-password" required maxlength="1024"></label><button>Entrar com segurança</button></form><p id="login-msg" class="msg" role="status"></p>
</section>
<section id="dashboard" hidden>
<div class="welcome"><div><h1>Painel administrativo</h1><p id="who"></p></div><button id="logout" class="secondary">Sair</button></div>
<nav class="tabs"><button data-tab="products" class="active">Produtos e estoque</button><button data-tab="orders">Pedidos</button></nav>
<section id="products-panel" class="panel">
<div class="panel-head"><h2>Catálogo</h2><button id="new-product">Novo produto</button></div>
<form id="product-form" class="card form-grid" hidden><h3 id="form-title">Novo produto</h3><input type="hidden" id="product-id"><label>Nome<input id="p-name" required maxlength="120"></label><label>Categoria<input id="p-category" required maxlength="80"></label><label>Preço (R$)<input id="p-price" type="number" min="0" max="1000000" step="0.01" required></label><label>Estoque<input id="p-stock" type="number" min="0" max="1000000" step="1" required></label><label class="wide">Descrição<textarea id="p-description" maxlength="2000" rows="3"></textarea></label><label class="wide">Imagem (URL HTTPS ou caminho local /imagens/foto.jpg)<input id="p-image" type="text" maxlength="1000"></label><label class="check"><input id="p-active" type="checkbox" checked> Produto ativo</label><div class="actions wide"><button type="submit">Salvar no banco</button><button id="cancel-edit" type="button" class="secondary">Cancelar</button></div><p id="product-msg" class="msg wide" role="status"></p></form>
<div id="product-list" class="list"></div>
</section>
<section id="orders-panel" class="panel" hidden><div class="panel-head"><h2>Pedidos</h2><button id="refresh-orders" class="secondary">Atualizar</button></div><div id="order-list" class="list"></div></section>
<p id="global-msg" class="msg" role="status"></p>
</section>
</main><footer>As operações administrativas são validadas no servidor. Use HTTPS e uma conta exclusiva em produção.</footer>
</body></html>

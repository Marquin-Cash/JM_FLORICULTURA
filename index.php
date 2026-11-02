<?php
require_once __DIR__.'/../config/db.php';
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Cache-Control: no-store');
?>
<!DOCTYPE html>
<html lang="pt-BR">
 <head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1, viewport-fit=cover" name="viewport"/>
  <title>
   J&M Floricultura Floricultura
  </title>
  <link href="https://fonts.googleapis.com" rel="preconnect"/>
  <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@400;600&amp;family=DM+Sans:wght@400;500;700&amp;display=swap" rel="stylesheet"/>
  <link href="css/style.css" rel="stylesheet"/>
 <style>#av,#sm,.so,#cdw,[data-md="gg"],[data-md="ph"]{display:none!important}</style></head>
 <body>
  <!-- Biblioteca de ícones SVG reutilizados em botões, links e categorias. -->
  <svg aria-hidden="true" height="0" style="position:absolute" width="0">
   <symbol id="i-cart" viewBox="0 0 24 24">
    <path d="M6 8h12l-1 12H7L6 8zM9 8a3 3 0 016 0">
    </path>
   </symbol>
   <symbol id="i-user" viewBox="0 0 24 24">
    <circle cx="12" cy="8" r="4">
    </circle>
    <path d="M4 21c0-4 4-6 8-6s8 2 8 6">
    </path>
   </symbol>
   <symbol id="i-heart" viewBox="0 0 24 24">
    <path d="M12 20s-7-4.4-7-10a4 4 0 017-2.4A4 4 0 0119 10c0 5.6-7 10-7 10z">
    </path>
   </symbol>
   <symbol id="i-flower" viewBox="0 0 24 24">
    <circle cx="12" cy="12" r="2.4">
    </circle>
    <ellipse cx="12" cy="6" rx="2.6" ry="3.4" transform="rotate(0 12 12)">
    </ellipse>
    <ellipse cx="12" cy="6" rx="2.6" ry="3.4" transform="rotate(72 12 12)">
    </ellipse>
    <ellipse cx="12" cy="6" rx="2.6" ry="3.4" transform="rotate(144 12 12)">
    </ellipse>
    <ellipse cx="12" cy="6" rx="2.6" ry="3.4" transform="rotate(216 12 12)">
    </ellipse>
    <ellipse cx="12" cy="6" rx="2.6" ry="3.4" transform="rotate(288 12 12)">
    </ellipse>
   </symbol>
   <symbol id="i-tulip" viewBox="0 0 24 24">
    <path d="M12 21v-8M7 4c0 5 2 8 5 9 3-1 5-4 5-9-2 1-3 2-5 2s-3-1-5-2z">
    </path>
   </symbol>
   <symbol id="i-plant" viewBox="0 0 24 24">
    <path d="M12 14V9M12 9c0-3 2-5 5-5 0 3-2 5-5 5zM12 12c0-2-1.5-4-4-4 0 2 1.5 4 4 4zM7 14h10l-1.2 6H8.2L7 14z">
    </path>
   </symbol>
   <symbol id="i-vase" viewBox="0 0 24 24">
    <path d="M9 3h6M10 3v3c0 2-3 3-3 7a5 5 0 0010 0c0-4-3-5-3-7V3">
    </path>
   </symbol>
   <symbol id="i-drop" viewBox="0 0 24 24">
    <path d="M12 3s6 6.5 6 11a6 6 0 01-12 0c0-4.5 6-11 6-11z">
    </path>
   </symbol>
   <symbol id="i-sparkle" viewBox="0 0 24 24">
    <path d="M12 3l1.8 5.2L19 10l-5.2 1.8L12 17l-1.8-5.2L5 10l5.2-1.8L12 3zM19 16l.8 2.2L22 19l-2.2.8L19 22l-.8-2.2L16 19l2.2-.8L19 16z">
    </path>
   </symbol>
   <symbol id="i-gift" viewBox="0 0 24 24">
    <path d="M4 10h16v10H4zM3 7h18v3H3zM12 7v13M12 7C10 3 6 4 7.5 6.5M12 7c2-4 6-3 4.5-.5">
    </path>
   </symbol>
   <symbol id="i-grid" viewBox="0 0 24 24">
    <path d="M4 4h7v7H4zM13 4h7v7h-7zM4 13h7v7H4zM13 13h7v7h-7z">
    </path>
   </symbol>
   <symbol id="i-truck" viewBox="0 0 24 24">
    <path d="M2 6h11v9H2zM13 9h4l3 3v3h-7">
    </path>
    <circle cx="6" cy="17.5" r="1.8">
    </circle>
    <circle cx="16.5" cy="17.5" r="1.8">
    </circle>
   </symbol>
   <symbol id="i-chat" viewBox="0 0 24 24">
    <path d="M4 5h16v11H9l-5 4V5z">
    </path>
   </symbol>
   <symbol id="i-search" viewBox="0 0 24 24">
    <circle cx="11" cy="11" r="6">
    </circle>
    <path d="M16 16l5 5">
    </path>
   </symbol>
   <symbol id="i-pin" viewBox="0 0 24 24">
    <path d="M12 21s7-6 7-11a7 7 0 10-14 0c0 5 7 11 7 11z">
    </path>
    <circle cx="12" cy="10" r="2.5">
    </circle>
   </symbol>
   <symbol id="i-clock" viewBox="0 0 24 24">
    <circle cx="12" cy="12" r="9">
    </circle>
    <path d="M12 7v5l3 2">
    </path>
   </symbol>
   <symbol id="i-note" viewBox="0 0 24 24">
    <path d="M3 6h18v12H3zM3 7l9 7 9-7">
    </path>
   </symbol>
   <symbol id="i-box" viewBox="0 0 24 24">
    <path d="M3 8l9-5 9 5v8l-9 5-9-5V8zM3 8l9 5 9-5M12 13v8">
    </path>
   </symbol>
   <symbol id="i-chart" viewBox="0 0 24 24">
    <path d="M4 20V10M10 20V4M16 20v-7M21 20H3">
    </path>
   </symbol>
   <symbol id="i-tag" viewBox="0 0 24 24">
    <path d="M3 12V4h8l10 10-8 8L3 12z">
    </path>
    <circle cx="7.5" cy="8.5" r="1">
    </circle>
   </symbol>
   <symbol id="i-wa" viewBox="0 0 24 24">
    <path d="M17.47 14.38c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.16-.17.2-.35.22-.64.07-.3-.15-1.26-.46-2.39-1.47-.88-.79-1.48-1.76-1.65-2.06-.17-.3-.02-.46.13-.6.13-.14.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.03-.52-.07-.15-.67-1.61-.92-2.2-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.48 0 1.46 1.07 2.88 1.21 3.07.15.2 2.1 3.2 5.08 4.49.71.3 1.26.49 1.69.63.71.23 1.36.2 1.87.12.57-.09 1.76-.72 2-1.41.25-.7.25-1.29.17-1.41-.07-.12-.27-.2-.57-.35m-5.42 7.4h-.01a9.87 9.87 0 01-5.03-1.38l-.36-.21-3.74.98 1-3.65-.24-.37a9.86 9.86 0 01-1.51-5.26c0-5.45 4.44-9.88 9.89-9.88 2.64 0 5.12 1.03 6.99 2.9a9.82 9.82 0 012.89 6.99c0 5.45-4.44 9.88-9.89 9.88m8.41-18.3A11.82 11.82 0 0012.05 0C5.5 0 .16 5.34.16 11.89c0 2.1.55 4.14 1.59 5.95L.06 24l6.3-1.65a11.88 11.88 0 005.68 1.45h.01c6.55 0 11.89-5.34 11.9-11.9a11.82 11.82 0 00-3.48-8.41z" fill="currentColor" stroke="none">
    </path>
   </symbol>
   <symbol id="i-edit" viewBox="0 0 24 24">
    <path d="M4 20h4l11-11-4-4L4 16v4zM13.5 6.5l4 4">
    </path>
   </symbol>
   <symbol id="i-trash" viewBox="0 0 24 24">
    <path d="M4 7h16M9 7V4h6v3M6 7l1 13h10l1-13M10 11v6M14 11v6">
    </path>
   </symbol>
   <symbol id="i-check" viewBox="0 0 24 24">
    <path d="M5 12l5 5 9-10">
    </path>
   </symbol>
   <symbol id="i-plus" viewBox="0 0 24 24">
    <path d="M12 5v14M5 12h14">
    </path>
   </symbol>
   <symbol id="i-dots" viewBox="0 0 24 24">
    <g fill="currentColor" stroke="none">
     <circle cx="12" cy="5" r="1.8">
     </circle>
     <circle cx="12" cy="12" r="1.8">
     </circle>
     <circle cx="12" cy="19" r="1.8">
     </circle>
    </g>
   </symbol>
   <symbol id="i-store" viewBox="0 0 24 24">
    <path d="M4 9l1-5h14l1 5M4 9v11h16V9M4 9c0 2 3 2 4 0 1 2 3 2 4 0 1 2 3 2 4 0 1 2 4 2 4 0M10 20v-5h4v5">
    </path>
   </symbol>
   <symbol id="i-g" viewBox="0 0 48 48">
    <g stroke="none">
     <path d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z" fill="#EA4335">
     </path>
     <path d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z" fill="#4285F4">
     </path>
     <path d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z" fill="#FBBC05">
     </path>
     <path d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z" fill="#34A853">
     </path>
    </g>
   </symbol>
  </svg>
  <!-- Elementos globais: atalho de acessibilidade, efeitos e indicador de rolagem. -->
  <a class="skip" href="#loja">
   Ir para as flores
  </a>
  <canvas aria-hidden="true" id="fx">
  </canvas>
  <div aria-hidden="true" id="sp">
  </div>
  <!-- NAV BAR: marca, links principais, efeitos, conta e carrinho. -->
  <!-- NAV BAR: marca, navegação, efeitos visuais, conta e carrinho. -->
  <header>
   <a class="logo" href="#">
    J&M Floricultura
   </a>
   <nav aria-label="Principal">
    <a href="#loja">
     <svg aria-hidden="true" class="ic s">
      <use href="#i-flower">
      </use>
     </svg>
     Cat&aacute;logo
    </a>
    <a href="#amor">
     <svg aria-hidden="true" class="ic s">
      <use href="#i-heart">
      </use>
     </svg>
     Recado
    </a>
    <a href="#como">
     <svg aria-hidden="true" class="ic s">
      <use href="#i-chat">
      </use>
     </svg>
     Como funciona
    </a>
   </nav>
   <button aria-label="P&eacute;talas e cora&ccedil;&otilde;es em movimento" aria-pressed="true" class="ib" id="fxb" title="P&eacute;talas e cora&ccedil;&otilde;es">
    <svg aria-hidden="true" class="ic">
     <use href="#i-flower">
     </use>
    </svg>
   </button>
   <button class="btn g" id="ub">
    <svg aria-hidden="true" class="ic">
     <use href="#i-user">
     </use>
    </svg>
    <span id="ul">
     Entrar
    </span>
   </button>
   <button aria-label="Carrinho" class="btn" id="cb">
    <svg aria-hidden="true" class="ic">
     <use href="#i-cart">
     </use>
    </svg>
    <span id="cn">
     0
    </span>
   </button>
  </header>
  <!-- CONTEÚDO PRINCIPAL DA LOJA. -->
  <main>
   <div id="cv">
    <!-- HERO: primeira seção visual, com apresentação e chamada para o catálogo. -->
   <!-- HERO: apresentação principal e botões de ação. -->
   <section class="hero">
     <div>
      <h1>
       Flores, cestas e presentes para toda ocasi&atilde;o
      </h1>
      <p>
       Buqu&ecirc;s, arranjos e cestas personalizadas, com recado no cart&atilde;o, entregues na sua porta.
      </p>
      <div class="acts">
       <a class="btn" href="#loja">
        <svg aria-hidden="true" class="ic">
         <use href="#i-flower">
         </use>
        </svg>
        Ver o cat&aacute;logo
       </a>
       <a class="btn g" href="#amor">
        <svg aria-hidden="true" class="ic">
         <use href="#i-heart">
         </use>
        </svg>
        Escrever recado
       </a>
      </div>
     </div>
     <div class="hv" id="hv">
     </div>
    </section>
    <!-- Benefícios da floricultura exibidos logo abaixo do banner principal. -->
   <!-- Benefícios e diferenciais da floricultura. -->
   <section class="wrap perks" style="padding-top:8px;padding-bottom:0">
     <div>
      <svg aria-hidden="true" class="ic">
       <use href="#i-truck">
       </use>
      </svg>
      <span>
       <b>
        Entrega com data marcada
       </b>
       <small>
        Voc&ecirc; escolhe o dia
       </small>
      </span>
     </div>
     <div>
      <svg aria-hidden="true" class="ic">
       <use href="#i-flower">
       </use>
      </svg>
      <span>
       <b>
        Flores da esta&ccedil;&atilde;o
       </b>
       <small>
        Frescas e selecionadas
       </small>
      </span>
     </div>
     <div>
      <svg aria-hidden="true" class="ic">
       <use href="#i-note">
       </use>
      </svg>
      <span>
       <b>
        Recado no cart&atilde;o
       </b>
       <small>
        Escrito &agrave; m&atilde;o
       </small>
      </span>
     </div>
     <div>
      <svg aria-hidden="true" class="ic">
       <use href="#i-wa">
       </use>
      </svg>
      <span>
       <b>
        Atendimento no WhatsApp
       </b>
       <small>
        Tire d&uacute;vidas e pe&ccedil;a direto
       </small>
      </span>
     </div>
    </section>
    <!-- Atalhos visuais para navegar pelas categorias. -->
   <!-- Atalhos para as categorias de produtos. -->
   <section class="wrap" style="padding-bottom:0">
     <h2>
      Categorias
     </h2>
     <div class="tiles" id="tl">
     </div>
    </section>
    <!-- CATÁLOGO: busca, filtros e produtos renderizados pelo JavaScript. -->
   <section class="wrap" id="loja">
     <h2 id="lt" tabindex="-1">
      Nosso cat&aacute;logo
     </h2>
     <p hidden="" id="nb">
     </p>
     <p class="mu" id="fr">
     </p>
     <div class="bar">
      <div aria-label="Categorias" class="chips" id="ch" role="group">
      </div>
      <input aria-label="Buscar no cat&aacute;logo" id="q" placeholder="Buscar no cat&aacute;logo" type="search"/>
      <label class="ck">
       <input id="st" type="checkbox"/>
       S&oacute; em estoque
      </label>
     </div>
     <p aria-live="polite" class="mu" id="cnt">
     </p>
     <p id="catalog-status" role="status" aria-live="polite"></p><div id="gr">
     </div>
    </section>
    <!-- RECADO PERSONALIZADO: prévia do cartão e campos para a mensagem. -->
   <!-- RECADO PERSONALIZADO: formulário e prévia do cartão. -->
   <section id="amor">
     <div class="wrap">
      <h2>
       Diga com flores o que as palavras n&atilde;o alcan&ccedil;am
      </h2>
      <p>
       Escreva um recado para o cart&atilde;o e escolha as flores em seguida.
      </p>
      <div class="cartao" id="cd">
      </div>
      <label>
       Para
       <input autocomplete="off" id="np" maxlength="40" placeholder="Nome de quem vai receber"/>
      </label>
      <label>
       Recado
       <textarea id="nt" maxlength="120" placeholder="Escreva sua mensagem" rows="2"></textarea>
      </label>
      <fieldset class="dk">
       <legend>
        De
       </legend>
       <div class="chips">
        <button aria-pressed="true" class="chip" data-k="n" type="button">
         Meu nome
        </button>
        <button aria-pressed="false" class="chip" data-k="a" type="button">
         An&ocirc;nimo
        </button>
        <button aria-pressed="false" class="chip" data-k="m" type="button">
         Um admirador
        </button>
       </div>
       <input autocomplete="name" id="nd" maxlength="40" placeholder="Seu nome"/>
      </fieldset>
      <button class="btn r" id="sl">
       <span aria-hidden="true" class="hb">
        &hearts;
       </span>
       Guardar e escolher flores
      </button>
     </div>
    </section>
    <!-- Passo a passo para fazer um pedido. -->
   <section class="wrap" id="como">
     <h2>
      Como funciona
     </h2>
     <ol class="steps">
      <li>
       <b>
        <svg aria-hidden="true" class="ic s">
         <use href="#i-search">
         </use>
        </svg>
        Escolha
       </b>
       <span>
        Leia a descri&ccedil;&atilde;o de cada flor e veja quantas restam.
       </span>
      </li>
      <li>
       <b>
        <svg aria-hidden="true" class="ic s">
         <use href="#i-note">
         </use>
        </svg>
        Escreva
       </b>
       <span>
        Deixe um recado para o cart&atilde;o.
       </span>
      </li>
      <li>
       <b>
        <svg aria-hidden="true" class="ic s">
         <use href="#i-truck">
         </use>
        </svg>
        Receba
       </b>
       <span>
        Entregamos frescas, prontas para alegrar a semana.
       </span>
      </li>
     </ol>
    </section>
   </div>
   <!-- PAINEL ADMINISTRATIVO: estoque, pedidos, catálogo e configurações. -->
  <div hidden="" id="av">
    <div class="wrap">
     <div class="ah">
      <h2>
       Minha loja
      </h2>
      <div aria-label="Se&ccedil;&otilde;es" class="chips" role="group">
       <button aria-pressed="true" class="chip" data-t="vg">
        <svg aria-hidden="true" class="ic s">
         <use href="#i-chart">
         </use>
        </svg>
        Vis&atilde;o geral
       </button>
       <button aria-pressed="false" class="chip" data-t="es">
        <svg aria-hidden="true" class="ic s">
         <use href="#i-box">
         </use>
        </svg>
        Estoque
       </button>
       <button aria-pressed="false" class="chip" data-t="pe">
        <svg aria-hidden="true" class="ic s">
         <use href="#i-truck">
         </use>
        </svg>
        Pedidos
       </button>
       <button aria-pressed="false" class="chip" data-t="pr">
        <svg aria-hidden="true" class="ic s">
         <use href="#i-flower">
         </use>
        </svg>
        Cat&aacute;logo
       </button>
       <button aria-pressed="false" class="chip" data-t="cf">
        <svg aria-hidden="true" class="ic s">
         <use href="#i-store">
         </use>
        </svg>
        Loja
       </button>
       <button aria-pressed="false" class="chip" data-t="sc">
        <svg aria-hidden="true" class="ic s">
         <use href="#i-chart">
         </use>
        </svg>
        Segurança
       </button>
      </div>
     </div>
     <section data-tp="vg">
      <div class="kp" id="kp">
      </div>
      <div id="al">
      </div>
      <div class="g2">
       <div class="pn">
        <h3>
         Vendas dos &uacute;ltimos 7 dias
        </h3>
        <div class="bars" id="ch7">
        </div>
       </div>
       <div class="pn">
        <h3>
         Mais vendidos
        </h3>
        <div id="tp">
        </div>
       </div>
      </div>
      <div class="pn">
       <h3>
        Pagamentos por forma
       </h3>
       <div id="pgs">
       </div>
      </div>
     </section>
     <section data-tp="es" hidden="">
      <div id="al2">
      </div>
      <div class="chips" style="margin:14px 0">
       <button aria-pressed="true" class="chip" data-sf="a">
        Todos
       </button>
       <button aria-pressed="false" class="chip" data-sf="l">
        A repor
       </button>
       <button aria-pressed="false" class="chip" data-sf="o">
        Esgotados
       </button>
      </div>
      <div class="tw">
       <table>
        <thead>
         <tr>
          <th>
           Produto
          </th>
          <th>
           Estoque
          </th>
          <th>
           M&iacute;n.
          </th>
          <th>
           Situa&ccedil;&atilde;o
          </th>
          <th>
           Valor
          </th>
          <th>
           Vendidos
          </th>
          <th>
          </th>
         </tr>
        </thead>
        <tbody id="te">
        </tbody>
       </table>
      </div>
     </section>
     <section data-tp="pe" hidden="">
      <div class="tw" style="margin-top:14px">
       <table>
        <thead>
         <tr>
          <th>
           Pedido
          </th>
          <th>
           Cliente
          </th>
          <th>
           Itens
          </th>
          <th>
           Endere&ccedil;o
          </th>
          <th>
           Total
          </th>
          <th>
           Pagamento
          </th>
          <th>
           Entrega
          </th>
         </tr>
        </thead>
        <tbody id="to">
        </tbody>
       </table>
      </div>
     </section>
     <section data-tp="pr" hidden="">
      <div class="pn">
       <h3>
        Nova categoria
       </h3>
       <form class="rw" id="fc">
        <input aria-label="Nome da categoria" id="nc" maxlength="30" placeholder="Nome da categoria" style="flex:1;min-width:160px"/>
        <button class="btn">
         <svg aria-hidden="true" class="ic s">
          <use href="#i-plus">
          </use>
         </svg>
         Criar
        </button>
       </form>
      </div>
      <div class="pn">
       <h3 id="fh">
        Novo item
       </h3>
       <form class="fm" id="af">
        <label>
         Nome
         <input name="n" required=""/>
        </label>
        <label>
         Categoria
         <select name="c">
         </select>
        </label>
        <label>
         Pre&ccedil;o (R$)
         <input min="0" name="p" required="" step="0.01" type="number"/>
        </label>
        <label>
         Estoque
         <input min="0" name="s" required="" type="number" value="10"/>
        </label>
        <label>
         M&iacute;nimo
         <input min="0" name="m" type="number" value="3"/>
        </label>
        <label>
         Foto
         <input accept="image/*" name="i" type="file"/>
        </label>
        <label>
         Cor principal
         <input name="a" type="color" value="#c2456b"/>
        </label>
        <label>
         Cor de detalhe
         <input name="b" type="color" value="#f4a9b8"/>
        </label>
        <label class="full">
         Descri&ccedil;&atilde;o
         <textarea name="d" required="" rows="2"></textarea>
        </label>
        <label class="full">
         Detalhes
         <textarea name="x" placeholder="Opcional: tamanho, cuidados, ocasi&otilde;es" rows="2"></textarea>
        </label>
        <label class="ck">
         <input name="ri" type="checkbox"/>
         Remover foto
        </label>
        <button class="btn" id="sv">
         Adicionar
        </button>
        <button class="btn g" hidden="" id="cx" type="button">
         Cancelar
        </button>
       </form>
      </div>
      <div id="tb">
      </div>
     </section>
     <section data-tp="cf" hidden="">
      <div class="pn">
       <h3>
        Minha conta
       </h3>
       <p class="mu">
        Nome, usu&aacute;rio, e-mail, telefone e senha.
       </p>
       <button class="btn" data-act="ac">
        <svg aria-hidden="true" class="ic s">
         <use href="#i-user">
         </use>
        </svg>
        Abrir minha conta
       </button>
      </div>
      <div class="pn">
       <h3>
        Dados da loja
       </h3>
       <form class="fm" id="sf">
        <label>
         Nome da loja
         <input name="nm"/>
        </label>
        <label>
         WhatsApp
         <input inputmode="tel" name="wa"/>
        </label>
        <label>
         Endere&ccedil;o
         <input name="ad"/>
        </label>
        <label>
         Hor&aacute;rio
         <input name="hr"/>
        </label>
        
        
        <button class="btn">
         Salvar
        </button>
       </form>
      </div>
     </section>
     <!-- Checklist de segurança: acompanhamento local dos controles de desenvolvimento. -->
     <section data-tp="sc" hidden="">
      <div class="pn security-intro">
       <h3>Checklist de segurança do sistema</h3>
       <p class="mu">Acompanhe os controles de segurança do projeto, filtre por categoria e registre evidências. O status fica salvo neste navegador.</p>
       <p class="security-warning"><strong>Atenção:</strong> este painel é uma ferramenta de acompanhamento. Como os dados são salvos no navegador, ele não comprova que um controle foi implementado nem substitui uma auditoria técnica.</p>
       <div class="security-progress" aria-live="polite">
        <div class="security-progress-head"><strong id="sec-count">0 de 100 controles avaliados</strong><span id="sec-percent">0%</span></div>
        <div class="security-progress-track"><span id="sec-progress"></span></div>
       </div>
       <div class="security-tools">
        <label>Buscar controle <input id="sec-search" type="search" placeholder="Ex.: XSS, senhas, backups..."></label>
        <label>Categoria
         <select id="sec-category"><option value="">Todas as categorias</option></select>
        </label>
        <label>Status
         <select id="sec-status">
          <option value="">Todos os status</option>
          <option value="nao-avaliado">Não avaliado</option>
          <option value="em-andamento">Em andamento</option>
          <option value="implementado">Implementado</option>
          <option value="nao-aplicavel">Não aplicável</option>
         </select>
        </label>
        <button class="btn" id="sec-export" type="button">Exportar CSV</button>
        <button class="btn g" id="sec-reset" type="button">Limpar avaliações</button>
       </div>
      </div>
      <div id="sec-list" class="security-list" aria-live="polite"></div>
     </section>
    </div>
   </div>
  </main>
  <!-- Rodapé preenchido com os dados cadastrados da floricultura. -->
  <!-- Rodapé com os dados da loja, preenchido pelo JavaScript. -->
  <footer class="wrap mu" id="ft">
  </footer>
  <!-- Janelas modais: login, carrinho, conta, pedido e detalhes. -->
  <dialog aria-labelledby="dlt" id="dl">
   <div class="dh">
    <h2 id="dlt">
     Sua conta
    </h2>
    <button aria-label="Fechar" class="ib" data-act="x">
     ✕
    </button>
   </div>
   <form class="db" id="lf" novalidate="">
    <label data-md="up">
     Seu nome
     <input autocomplete="name" id="nm"/>
    </label>
    <label data-md="in" hidden="">
     E-mail
     <input autocomplete="username" id="id"/>
    </label>
    <label data-md="up">E-mail ou usuário<input id="register-email" type="email" autocomplete="email" maxlength="254"/></label>
    <label data-md="up ph">
     Telefone
     <input autocomplete="tel" id="tf" inputmode="tel" type="tel"/>
    </label>
    <label data-md="ph" hidden="" id="cdw">
     C&oacute;digo recebido
     <input autocomplete="one-time-code" id="cd2" inputmode="numeric" maxlength="6"/>
    </label>
    <label data-md="gg" hidden="">
     E-mail do Google
     <input autocomplete="email" id="ge" type="email"/>
    </label>
    <label data-md="gg" hidden="">
     Seu nome
     <input autocomplete="name" id="gn"/>
    </label>
    <label data-md="up in">
     Senha
     <input autocomplete="new-password" id="pw" type="password" minlength="8" maxlength="72"/>
    </label>
    <p class="low" id="er" role="alert">
    </p>
    <p class="mu" id="inf" role="status">
    </p>
    <button class="btn" id="sb">
     Criar conta
    </button>
    <button class="lk" id="tg" type="button">
     J&aacute; tenho conta
    </button>
    <div aria-label="Entrar com" class="so" role="group">
     <span class="mu">
      ou entre com
     </span>
     <button aria-label="Entrar com o Google" class="rb" id="gb" type="button">
      <svg aria-hidden="true" class="ic">
       <use href="#i-g">
       </use>
      </svg>
     </button>
     <button aria-label="Entrar com o WhatsApp" class="rb" id="wb" type="button">
      <svg aria-hidden="true" class="ic">
       <use href="#i-wa">
       </use>
      </svg>
     </button>
    </div>
   </form>
  </dialog>
  <dialog aria-labelledby="dct" id="dc">
   <div class="dh">
    <h2 id="dct">
     Seu carrinho
    </h2>
    <button aria-label="Fechar" class="ib" data-act="x">
     ✕
    </button>
   </div>
   <div class="db">
    <div id="ci">
    </div>
    <div class="db" id="ct">
    </div>
    <div class="db" id="gf" style="padding:0">
     <label>
      Seu nome
      <input autocomplete="name" id="gn2"/>
     </label>
     <label>
      Telefone
      <input autocomplete="tel" id="gt" inputmode="tel" type="tel"/>
     </label>
    </div>
    <label>
     Endere&ccedil;o de entrega
     <input autocomplete="street-address" id="ad"/>
    </label>
    <label>
     Data da entrega
     <input id="dt" type="date"/>
    </label>
    <label>
     Forma de pagamento
     <select id="pg">
      <option>
       Pix
      </option>
      <option>
       Cart&atilde;o de cr&eacute;dito
      </option>
      <option>
       Cart&atilde;o de d&eacute;bito
      </option>
      <option>
       Dinheiro na entrega
      </option>
     </select>
    </label>
    <p class="low" id="ce" role="alert">
    </p>
    <button class="btn" id="ck">
     Finalizar pedido
    </button>
    <a class="btn wa" href="#" id="cw" rel="noopener" target="_blank">
     <svg aria-hidden="true" class="ic">
      <use href="#i-wa">
      </use>
     </svg>
     Comprar pelo WhatsApp
    </a>
   </div>
  </dialog>
  <dialog aria-labelledby="dat" id="da">
   <div class="dh">
    <h2 id="dat">
     Minha conta
    </h2>
    <button aria-label="Fechar" class="ib" data-act="x">
     ✕
    </button>
   </div>
   <form class="db" id="fa" novalidate="">
    <label>
     Nome
     <input autocomplete="name" id="an"/>
    </label>
    <label>
     Usu&aacute;rio
     <input autocomplete="username" id="au" disabled/>
    </label>
    <label>
     E-mail
     <input autocomplete="email" id="ae" type="email"/>
    </label>
    <label>
     Telefone
     <input autocomplete="tel" id="at" inputmode="tel" type="tel"/>
    </label>
    <label>
     Senha atual
     <input autocomplete="current-password" id="ac" type="password"/>
    </label>
    <label>
     Nova senha
     <input autocomplete="new-password" id="ap" placeholder="Manter a atual" type="password"/>
    </label>
    <p class="low" id="ar" role="alert">
    </p>
    <button class="btn">
     Salvar altera&ccedil;&otilde;es
    </button>
    <button class="lk" id="lo" type="button">
     Sair da conta
    </button>
   </form>
  </dialog>
  <dialog aria-labelledby="dot" id="do">
   <div class="dh">
    <h2 id="dot">
     Pedido confirmado
    </h2>
    <button aria-label="Fechar" class="ib" data-act="x">
     ✕
    </button>
   </div>
   <div class="db">
    <p>
     Seu pedido foi registrado. Você também pode combinar os detalhes pelo WhatsApp.
    </p>
    <p class="low" id="owm" role="alert">
    </p>
    <a class="btn wa" href="#" id="ow" rel="noopener" target="_blank">
     <svg aria-hidden="true" class="ic">
      <use href="#i-wa">
      </use>
     </svg>
     Enviar pedido
    </a>
    <button class="lk" data-act="x">
     Agora n&atilde;o
    </button>
   </div>
  </dialog>
  <dialog aria-labelledby="dnt" id="dn">
   <div class="dh">
    <h2 id="dnt">
     Quer escrever um recado?
    </h2>
    <button aria-label="Fechar" class="ib" data-act="x">
     ✕
    </button>
   </div>
   <form class="db" id="fn" novalidate="">
    <p class="mu">
     Ele vai no cart&atilde;o que acompanha o presente.
    </p>
    <label>
     Para
     <input autocomplete="off" id="np2" maxlength="40" placeholder="Nome de quem vai receber"/>
    </label>
    <label>
     Recado
     <textarea id="nt2" maxlength="120" placeholder="Escreva sua mensagem" rows="2"></textarea>
    </label>
    <div aria-label="De" class="chips" role="group">
     <button aria-pressed="true" class="chip" data-k2="n" type="button">
      Meu nome
     </button>
     <button aria-pressed="false" class="chip" data-k2="a" type="button">
      An&ocirc;nimo
     </button>
     <button aria-pressed="false" class="chip" data-k2="m" type="button">
      Admirador
     </button>
    </div>
    <input autocomplete="name" id="nd2" maxlength="40" placeholder="Seu nome"/>
    <p class="low" id="nr" role="alert">
    </p>
    <button class="btn">
     Guardar recado
    </button>
    <button class="lk" data-act="x" type="button">
     Agora n&atilde;o
    </button>
   </form>
  </dialog>
  <dialog aria-labelledby="dxt" id="dx">
   <div class="dh">
    <h2 id="dxt">
     Apagar categoria?
    </h2>
    <button aria-label="Fechar" class="ib" data-act="x">
     ✕
    </button>
   </div>
   <div class="db">
    <p id="dxm">
    </p>
    <div class="rw">
     <button class="btn g" data-act="x">
      Cancelar
     </button>
     <button class="btn" id="dxo">
      <svg aria-hidden="true" class="ic s">
       <use href="#i-trash">
       </use>
      </svg>
      Apagar tudo
     </button>
    </div>
   </div>
  </dialog>
  <dialog aria-labelledby="dpt" id="dp">
   <div class="dh">
    <h2 id="dpt">
     Detalhes
    </h2>
    <button aria-label="Fechar" class="ib" data-act="x">
     ✕
    </button>
   </div>
   <div class="db" id="dpb">
   </div>
  </dialog>
  <!-- Controles flutuantes: resumo do carrinho, menu lateral, WhatsApp e avisos. -->
  <div class="cbar" hidden="" id="cbar">
   <span id="cbt">
   </span>
   <button class="btn" id="cbb">
    <svg aria-hidden="true" class="ic s">
     <use href="#i-cart">
     </use>
    </svg>
    Ver carrinho
   </button>
  </div>
  <div class="sm" hidden="" id="sm">
   <button aria-expanded="false" aria-haspopup="menu" aria-label="Menu lateral" class="sk" id="sk">
    <svg aria-hidden="true" class="ic">
     <use href="#i-dots">
     </use>
    </svg>
   </button>
   <div hidden="" id="sml" role="menu">
    <button data-act="vw" data-v="a" role="menuitem">
     <svg aria-hidden="true" class="ic">
      <use href="#i-store">
      </use>
     </svg>
     Minha loja
    </button>
    <button aria-current="true" data-act="vw" data-v="c" role="menuitem">
     <svg aria-hidden="true" class="ic">
      <use href="#i-cart">
      </use>
     </svg>
     Loja cliente
    </button>
    <button data-act="ac" id="mc" role="menuitem">
     <svg aria-hidden="true" class="ic">
      <use href="#i-user">
      </use>
     </svg>
     Minha conta
    </button>
   </div>
  </div>
  <a aria-label="Falar com a loja pelo WhatsApp" class="wf" href="#" id="wf" rel="noopener" target="_blank">
   <svg aria-hidden="true" class="ic">
    <use href="#i-wa">
    </use>
   </svg>
  </a>
  <div aria-live="polite" id="toast" role="status">
  </div>
  <!-- JavaScript: comportamento da loja, formulários, carrinho e animações. -->
  <p class="admin-entry" style="text-align:center;padding:12px"><a href="admin.php">Área administrativa</a></p>
  <script>window.JM_STORE = {};</script>
  <script src="js/script.js?v=20261019-2">
  </script>
  
  <script src="js/backend-store.js?v=20261019-2" defer>
  </script>
 </body>
</html>

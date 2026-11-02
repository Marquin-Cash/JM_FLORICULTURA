# 🌸 J&M Floricultura — Sistema web

Projeto de portfólio de uma floricultura com catálogo, busca, categorias, carrinho e painel administrativo, desenvolvido com **PHP, PostgreSQL, HTML, CSS e JavaScript**.

## Demonstração

A pasta `netlify-demo` (distribuída separadamente) é uma **demonstração estática** do catálogo, sem acesso ao banco de dados. A versão completa utiliza API PHP e PostgreSQL.

## Funcionalidades

- Vitrine responsiva, pesquisa, categorias e imagens de produtos
- Carrinho e mensagem de pedido via WhatsApp
- Backend PHP para catálogo, pedidos e autenticação
- Área administrativa para gerenciamento (somente em servidor PHP)
- Integração com PostgreSQL

## Tecnologias

PHP 8+, PostgreSQL, PDO, HTML5, CSS3 e JavaScript.

## Executar localmente com XAMPP

1. Copie o projeto para `C:\xampp\htdocs\jmf`.
2. Habilite `pdo_pgsql` e `pgsql` no `C:\xampp\php\php.ini` e reinicie o Apache.
3. Configure o PostgreSQL e prepare o esquema esperado pelo projeto (`jm_app`). **Atenção:** uma tabela antiga `public.produtos` não equivale automaticamente ao esquema `jm_app`.
4. Copie `config/local.example.php` para `config/local.php` e informe as credenciais locais.
5. Abra `http://localhost/jmf/public/`.

Para produção, configure o **DocumentRoot** diretamente para a pasta `public/` e mantenha `config/`, `app/` e `bin/` fora da área pública. Os scripts de preparação do banco ficam em `bin/`; revise-os antes de executar em banco com dados existentes.

## Segurança

- `config/local.php`, `.env` e arquivos de backup são ignorados pelo Git.
- Nunca envie senhas, dados reais de clientes, dumps de produção ou sessões para o repositório.
- Se credenciais já foram expostas em um repositório ou compartilhamento, **troque-as** antes de publicar; `.gitignore` não apaga histórico antigo.
- Não publique a raiz do projeto como diretório web.
- Revise autenticação, permissões e logs antes de utilizar em produção.

## Estrutura

```text
app/             Lógica e inicialização
bin/             Scripts auxiliares de banco
config/          Configuração (local.php privado)
public/          Páginas, APIs, CSS, JS e imagens
```

## Limites da demonstração

A versão Netlify usa catálogo JSON de exemplo. O carrinho funciona no navegador e o pedido abre uma mensagem no WhatsApp; **não grava pedidos, não reserva estoque e não possui autenticação ou painel administrativo**. Valores e disponibilidade são ilustrativos e devem ser confirmados com a loja.

## Créditos

Projeto acadêmico/de portfólio J&M Floricultura. Fotos e identidade visual pertencem aos respectivos titulares; confirme autorização de uso público antes da divulgação.

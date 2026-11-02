# J&M Floricultura — PHP + PostgreSQL

Comece por **ATUALIZACAO.md**: contém os cinco ajustes, o comando de instalação/atualização no seu XAMPP e os endereços para acessar a loja e o painel.

| Caminho | Finalidade |
| --- | --- |
| public/index.php | Loja, formulários de conta e carrinho |
| public/admin.php | Produtos, estoque, pedidos e acesso administrativo |
| public/css | Estilos |
| public/js/script.js | Interface, animações, localização e WhatsApp |
| public/js/backend-store.js | Comunicação da loja com o backend |
| public/js/admin.js | Comunicação do painel com o backend |
| public/api/auth.php | Cadastro, login, edição de perfil e logout |
| public/api/products.php | Consulta e administração de produtos |
| public/api/orders.php | Pedidos com frete a combinar |
| config/local.php | Credenciais privadas do PostgreSQL |
| config/db.php | Conexão PDO |
| app/bootstrap.php | Sessões, permissões e validações |
| bin/atualizar.php | Preparar banco, importar catálogo e configurar admin |
| bin/preparar_banco.php | Migração e importação sem redefinir senha administrativa |
| bin/configurar_admin.php | Configurar admin com a senha solicitada |
| bin/create_admin.php | Criar outras contas administrativas com e-mail pelo terminal |
| bin/verificar.php | Conferir extensões, conexão e tabelas |
| public/assets/images | Fotos preservadas dos projetos |

Requisitos: PHP 8.1 ou superior, extensões pdo_pgsql e mbstring, PostgreSQL com o banco jm_floricultura. O pacote não contém backup do seu banco. A importação ocorre localmente, com seus dados reais.

Cadastro público usa nome, e-mail, telefone e senha. Login social por Google/WhatsApp não está implementado. O pedido registra a forma de pagamento desejada; não cobra cartão nem confirma Pix automaticamente. O vendedor combina frete e valor final no WhatsApp.

A criação de administrador e a preparação do banco só funcionam pelo terminal. O site usa o esquema jm_app; as tabelas public.produtos e public.categorias permanecem intactas. Alterações posteriores nas tabelas antigas não sincronizam com registros já importados.

As páginas são PHP e contêm a marcação HTML necessária para exibir a interface. CSS e JavaScript continuam necessários. Não há arquivos .html avulsos nem esquema MySQL.

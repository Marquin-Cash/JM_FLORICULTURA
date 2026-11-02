# Atualização — frete, catálogo, marca, mapa e administrador

## Aplicar no seu XAMPP

1. Guarde uma cópia da pasta anterior.
2. Extraia este ZIP. Copie o CONTEÚDO de `JM_FLORICULTURA_PHP_POSTGRES` para `C:\xampp\htdocs\xampp\JM_FLORICULTURA_PHP_POSTGRES`, substituindo os arquivos. Não crie outra pasta com o mesmo nome dentro dela.
3. Se você alterou `config/local.php` no computador, preserve sua versão. A atualização não exige novos campos nele. O pacote mantém os dados de conexão fornecidos anteriormente.
4. Abra o PowerShell e execute:

```powershell
& "C:\xampp\php\php.exe" "C:\xampp\htdocs\xampp\JM_FLORICULTURA_PHP_POSTGRES\bin\atualizar.php"
```

Esse comando usa o PHP do XAMPP, verifica `pdo_pgsql` e `mbstring`, prepara/atualiza as tabelas, importa os produtos antigos, configura o usuário administrativo solicitado e verifica a conexão. Não apaga tabelas nem pedidos existentes.

Se informar que falta uma extensão, abra `C:\xampp\php\php.ini` e habilite as linhas abaixo, retirando o `;` inicial quando existir:

```ini
extension=pdo_pgsql
extension=mbstring
```

Salve, reinicie o Apache e execute novamente o comando. Se houver outro erro, copie a saída completa do terminal. Não é necessário iniciar o servidor PHP na porta 8000.

5. Abra a loja e pressione Ctrl+F5:

http://localhost/xampp/JM_FLORICULTURA_PHP_POSTGRES/public/index.php

Administração:

http://localhost/xampp/JM_FLORICULTURA_PHP_POSTGRES/public/admin.php

- Usuário: `admin`
- Senha: `admin 123` (com um espaço)

A conta é gravada no PostgreSQL quando o comando é executado. A senha é armazenada como hash. O cadastro público não permite criar administradores. `admin` é um nome de usuário, por isso o campo administrativo aceita usuário ou e-mail. Executar novamente `bin/atualizar.php` restaura a senha solicitada para a conta admin.

## Alterações

1. Frete sem valor fixo e sem gratuidade por faixa de compra. Loja, carrinho, confirmação, WhatsApp e painel mostram subtotal sem entrega e frete a combinar conforme o bairro. O backend grava `shipping_amount=NULL`, e novos pedidos usam `pricing_mode=quote_pending`; não representa frete zero. A API retorna `total=NULL` porque o total final ainda depende do vendedor. O campo legado `orders.total`, nos novos pedidos, contém somente o valor dos produtos por compatibilidade; use `subtotal` e `pricing_mode` para interpretar. Pedidos anteriores mantêm seus valores e ficam identificados como `legacy_total`.
2. Catálogo: preparação e importação em uma rotina, indicação específica quando faltam tabelas/colunas, mensagem de falha distinta de uma busca sem resultados. A causa original só poderá ser confirmada pelo log/terminal: o erro genérico informado não identifica qual consulta falhou. A atualização não mascara falhas de conexão com dados de demonstração.
3. Nome alterado para J&M Floricultura.
4. Localização no rodapé sublinhada e vinculada a https://maps.app.goo.gl/NaYr1wmbS8GGxL8X9.
5. Administrador configurável pelo comando acima com as credenciais solicitadas.

## Produtos

O site utiliza `jm_floricultura > jm_app > products`. A rotina importa novos registros de `public.produtos` por `legacy_id`, sem sobrescrever os já importados. Se o catálogo permanecer vazio depois dessa importação, importa os 38 produtos do catálogo do novo ZIP. Não mistura automaticamente esses 38 itens com um catálogo já preenchido.

Quando não existe estoque no cadastro original, o estoque inicial é 0: o produto aparece esgotado até você ajustar a quantidade real pelo painel. Mantenha “Só em estoque” desmarcado para visualizar todos os itens ativos.

Fotos locais ausentes geram aviso, mas não impedem a importação dos produtos; eles usam a ilustração padrão. As imagens disponíveis no pacote original foram preservadas. Caminhos `/assets/...` passam a ser resolvidos relativamente à pasta pública do site, corrigindo a exibição no XAMPP com subpastas.

No painel, use caminhos como `/assets/images/produtos/buque_10_rosas.jpeg` ou URL HTTP(S).

## Apache

O `public/.htaccess` contém apenas `Require all granted`, compatível com a configuração que funcionou no seu computador. O `.htaccess` da raiz mantém `Require all denied` para proteger os arquivos privados. Use sempre a URL com `/public/index.php`.

## Verificação

JavaScript verificado com `node --check`. Testados isoladamente os cálculos de subtotal, mensagem de WhatsApp e resolução de imagens em subpastas. Não foi possível executar PHP/PostgreSQL neste ambiente; a conexão real e os fluxos completos dependem da execução no computador do usuário.

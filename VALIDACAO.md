# Validação da integração

Realizado:
- Leitura e comparação dos dois ZIPs.
- Verificação de sintaxe dos três arquivos JavaScript com `node --check`: passou.
- Verificação das referências simples de IDs usadas pelos scripts nas duas páginas: nenhum ID ausente ou duplicado.
- Verificação da existência dos arquivos CSS/JS referenciados: passou.
- Revisão das consultas para PDO PostgreSQL, uso de RETURNING, limites de tentativas com ON CONFLICT, sessão, papel de administrador e cadastro de cliente sem aceitar papel enviado pelo navegador.
- Preservação das fotos antigas e extração das 38 imagens do catálogo novo.

Não executado neste ambiente:
- `php -l` e execução PHP: interpretador não disponível.
- Conexão/consultas contra PostgreSQL real: servidor e dump do banco local não disponíveis.
- Teste visual e fluxos em navegador: executável de navegador não disponível.

Portanto, os testes estáticos não comprovam funcionamento ponta a ponta. Execute a sequência de instalação e o roteiro do LEIA-ME no computador com PHP/PostgreSQL. O script `bin/verificar.php` valida as extensões, conexão e tabelas. A importação é transacional e aborta com mensagem se encontrar incompatibilidade, preservando as tabelas originais.

## Ajustes solicitados em 19/10/2026

- Sintaxe dos três scripts JavaScript: passou.
- Testes isolados executados com Node: subtotal com preços de 50, 150 e 200 reais, carrinho vazio, frete NULL em todas as faixas, texto do WhatsApp sem gratuidade, resolução de imagens em subpastas, nome da loja e mapa sublinhado: passaram.
- Referências simples dos scripts aos IDs das duas páginas: sem ausências ou duplicações.
- Revisão estática do INSERT de pedidos: nove parâmetros e nove valores; frete e total final indefinidos na resposta da API.
- PHP e PostgreSQL continuam sem execução neste ambiente. A instalação, conta admin e catálogo real precisam ser validados com bin/atualizar.php no XAMPP do usuário.

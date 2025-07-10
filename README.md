# ailos-sdk-php
Este SDK foi desenvolvido para facilitar a integração com os serviços da cooperativa Ailos, oferecendo uma interface simples e eficiente para interagir com as APIs da Ailos.
![ailos-sdk-php-logo](https://github.com/user-attachments/assets/83dc22d7-5d17-45a0-be81-11a052d90ceb)

# Documentação

## Informações Gerais

### Chamadas e Chamadas Tratadas

Salvo exceções específicas, todas as chamadas de API no SDK seguem dois formatos:

#### 🔹 Funções de alto nível (`get*`)

Funções com o prefixo `get` realizam a chamada à API, tratam a resposta, validam campos obrigatórios e executam ações adicionais (como configurar headers automaticamente). Elas retornam um objeto `ApiResponse` com os dados prontos para uso.

São ideais para quem busca praticidade e uma resposta já estruturada.

```php
$retorno = getAccessToken($clientId, $clientSecret, true);
```

#### 🔸 Funções de baixo nível (sem prefixo)

Funções sem o prefixo `get` apenas executam a chamada bruta à API, retornando um objeto `ResponseInterface` (PSR-7).

São indicadas para desenvolvedores que desejam controle total sobre o tratamento da resposta (como status code, headers, corpo bruto, etc.).

```php
$retorno = accessToken($clientId, $clientSecret);
```

*⚠️ Esta regra se aplica apenas a funções que fazem chamadas HTTP externas (como nas classes `Auth` ou `Services`).
Métodos utilitários ou locais não seguem esse padrão.*
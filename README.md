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

*⚠️ Esta regra se aplica apenas a funções que fazem chamadas HTTP externas (como nas classes `Auth` ou `Service`).
Métodos utilitários ou locais não seguem esse padrão.*

## API Cobrança

### Configuração
Antes de realizar qual quer chamada para a API de cobrança você deve criar uma variavel de configurações. Ela é a responsavel por setar valores essenciais para realizar as chamadas da API.
```php
$config = new Config();
```

### Autenticação
Após setar as configurações você precisa gerar os tokens de autenticação para fazer as proximas chamadas de API, esse processo possui algumas etapas, mas antes vamos instanciar a classe de autenticação passando as nossas configurações como parametro. Essa classe de autenticação nós vamos utilizar para fazer as chamadas de autenticação da API.
```php
$config = new Config();

$auth = new Auth($config); #!
```

1. A primeira etapa para se autenticar na API de cobrança é obter o access token da API (1º token). É requisito obrigatório para fazer as proximas chamadas de autenticação ter esse token setado no header da requisição.
```php
$config = new Config();

$auth = new Auth($config);
$auth->getAccessToken($clientId, $clientSecret, true); #!
```

2. A segunda etapa é gerar um id. Esse id gerado é único e deve ser guardado para fazer o refresh do token futuramente.
```php
$config = new Config();

$auth = new Auth($config);
$auth->getAccessToken($clientId, $clientSecret, true);
$auth->getId($urlCallback, $ailosApiKeyDeveloper, $state); #!
```

3. A ultima etapa é gerar e instanciar o token final que vai ser utilizado para fazer as proximas chamadas. Esse token final vai ser enviado para a url de callback setada na etapa anterior.
```php
$config = new Config();

$auth = new Auth($config);
$auth->getAccessToken($clientId, $clientSecret, true);
$auth->getId($urlCallback, $ailosApiKeyDeveloper, $state);
$auth->getAuth($id, $loginCoopCode, $loginAccountCode, $loginPassword); #!
```

### Próximas Chamadas
Para realizar as proximas chamadas é fundamental que você instancie o ultimo token gerado no header da API.
```php
$config = new Config();

$auth = new Auth($config);
$auth->getAccessToken($clientId, $clientSecret, true);
$auth->getId($urlCallback, $ailosApiKeyDeveloper, $state);
$auth->getAuth($id, $loginCoopCode, $loginAccountCode, $loginPassword);

$config->setDefaultHeaders(["Authorization" => "Bearer SEU_TOKEN"]); #!
```

### Refresh
Para fazer o refresh do token você vai precisar manter o ultimo token gerado (o token que vai ser recarregado) no header e chamar a função de refresh passando o id como parametro.
```php
$config = new Config();

$auth = new Auth($config);
$auth->getAccessToken($clientId, $clientSecret, true);
$auth->getId($urlCallback, $ailosApiKeyDeveloper, $state);
$auth->getAuth($id, $loginCoopCode, $loginAccountCode, $loginPassword);

$config->setDefaultHeaders(["Authorization" => "Bearer SEU_TOKEN"]);

$auth->getRefresh($id); #!
```

### Pagadores
Agora para fazer as chamadas da API de Cobrança você precisa instanciar uma nova classe passando a configuração, após fazer a autenticação.
```php
$config = new Config();

$auth = new Auth($config);
$auth->getAccessToken($clientId, $clientSecret, true);
$auth->getId($urlCallback, $ailosApiKeyDeveloper, $state);
$auth->getAuth($id, $loginCoopCode, $loginAccountCode, $loginPassword);

$config->setDefaultHeaders(["Authorization" => "Bearer SEU_TOKEN"]);

$service = new Service($config); #!
```

#### Cadastrar Novo Pagador
Para cadastrar um pagador é necessario que primeiro você monte o modelo que vai ser enviado como parametro, esse modelo é o objeto do pagador que você deseja cadastrar.
```php
$service = new Service($config);

$pagador = new Pagador(
    new EntidadeLegal('123', 1, 'João'),
    new Telefone('55', '11', '999999999'),
    new Endereco(...),
    ['mensagem...'],
    true
);

$service->getCadastrarPagador($pagador);
```

#### Alterar Pagador
```php
$service = new Service($config);

$pagador = new Pagador(
    new EntidadeLegal('123', 1, 'João'),
    new Telefone('55', '11', '999999999'),
    new Endereco(...),
    ['mensagem...'],
    true
);

$service->getAlterarPagador($pagador);
```

#### Consultar Pagador
Você pode consultar um pagador a partir de um documento (CPF ou CNPJ).
```php
$service = new Service($config);
$service->getConsultarPagador($documento);
```

#### Listar Pagadores
```php
$service = new Service($config);
$service->getListarPagadores($documento);
```

#### Totalizar Pagadores
```php
$service = new Service($config);
$service->getTotalizarPagadores($documento);
```

#### Exportar Pagadores
```php
$service = new Service($config);
$service->getExportarPagadores($tipoArquivo, $flagArquivoModelo);
```

#### Importar Pagadores
```php
$service = new Service($config);
$service->getImportarPagadores($arquivo, $codigoCanal, $ipAcionamento);
```
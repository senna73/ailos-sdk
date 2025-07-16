# ⚒️ Ailos SDK PHP
Este SDK foi desenvolvido para facilitar a integração com os serviços da **Cooperativa Ailos**, oferecendo uma interface simples, segura e eficiente para desenvolvedores PHP.

![Ailos SDK PHP](https://github.com/user-attachments/assets/c9ddc4f6-0a2d-4d52-8766-3a4f898d3ae3)

[![PHP Version](https://img.shields.io/badge/php-%3E%3D7.4-blue.svg)](https://www.php.net/)
[![Code Style](https://img.shields.io/badge/code_style-PSR--12-blue)](https://www.php-fig.org/psr/psr-12/)
[![License](https://img.shields.io/github/license/ViniciusDeSenna/ailos-sdk-php)](LICENSE)
[![Build Status](https://img.shields.io/github/actions/workflow/status/ViniciusDeSenna/ailos-sdk-php/php.yml?branch=main)](https://github.com/ViniciusDeSenna/ailos-sdk-php/actions)
[![Coverage](https://img.shields.io/codecov/c/github/ViniciusDeSenna/ailos-sdk-php)](https://codecov.io/gh/ViniciusDeSenna/ailos-sdk-php)
[![Maintenance](https://img.shields.io/maintenance/yes/2025)]()
[![Last Commit](https://img.shields.io/github/last-commit/ViniciusDeSenna/ailos-sdk-php)](https://github.com/ViniciusDeSenna/ailos-sdk-php/commits)
[![Packagist](https://img.shields.io/packagist/v/ViniciusDeSenna/ailos-sdk-php)](https://packagist.org/packages/ViniciusDeSenna/ailos-sdk-php)
[![Downloads](https://img.shields.io/packagist/dt/ViniciusDeSenna/ailos-sdk-php)](https://packagist.org/packages/ViniciusDeSenna/ailos-sdk-php)
[![Issues](https://img.shields.io/github/issues/ViniciusDeSenna/ailos-sdk-php)](https://github.com/ViniciusDeSenna/ailos-sdk-php/issues)
[![GitHub Stars](https://img.shields.io/github/stars/ViniciusDeSenna/ailos-sdk-php?style=social)](https://github.com/ViniciusDeSenna/ailos-sdk-php/stargazers)

# 🤝 Contribuindo
Pull requests são bem-vindos! Sinta-se à vontade para abrir issues e sugerir melhorias. 

Para contribuir:

1. Fork este repositório
2. Crie uma branch com sua feature: `git checkout -b minha-feature`
3. Commit suas mudanças: `git commit -m 'Adiciona nova feature'`
4. Push para o repositório: `git push origin minha-feature`
5. Abra um Pull Request

# 📖 Documentação

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
Antes de qualquer chamada, crie e configure a instância de `Config`. Ela armazena os dados essenciais para autenticação e comunicação com a API.
```php
$config = new Config();
```

### Autenticação
#### Instanciar classe de autenticação
Vamos instanciar a classe de autenticação passando as nossas configurações como parametro. Essa classe de autenticação nós vamos utilizar para fazer as chamadas de autenticação da API.
```php
$auth = new Auth($config);
```

#### Gerar Access Token
A primeira etapa para se autenticar na API de cobrança é obter o access token da API (1º token). O token de acesso é obrigatório para continuar com a autenticação.
```php
$auth->getAccessToken($clientId, $clientSecret, true);
```

#### Gerar ID de Sessão
Este ID é único e deve ser salvo para uso posterior (ex.: refresh do token).
```php
$auth->getId($urlCallback, $ailosApiKeyDeveloper, $state);
```

#### Autenticar com credenciais
O token final será enviado para a URL de callback definida anteriormente.
```php
$auth->getAuth($id, $loginCoopCode, $loginAccountCode, $loginPassword);
```

#### Enviar Token no Header
Inclua o token no header para as chamadas subsequentes.
```php
$config->setDefaultHeaders(["Authorization" => "Bearer SEU_TOKEN"]); #!
```

#### Refresh do Token
Mantenha o token atual no header e chame a função passando o ID da sessão:
```php
$auth->getRefresh($id);
```

### Instanciando Service de Cobrança
Após a autenticação, instancie o serviço de cobrança.
```php
$service = new Service($config);
```

### Pagadores
#### Cadastrar Pagador
Monte um objeto `Pagador` com os dados necessários.
```php
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
$service->getAlterarPagador($pagador);
```

#### Consultar Pagador
Consulta por documento (CPF ou CNPJ):
```php
$service->getConsultarPagador($documento);
```

#### Listar Pagadores
```php
$service = new Service($config);
$service->getListarPagadores($documento);
```

#### Totalizar Pagadores
```php
$service->getTotalizarPagadores($documento);
```

#### Exportar Pagadores
```php
$service->getExportarPagadores($tipoArquivo, $flagArquivoModelo);
```

#### Importar Pagadores
```php
$service->getImportarPagadores($arquivo, $codigoCanal, $ipAcionamento);
```

### Emissão e Consulta
#### Gerar Boleto
Monte um objeto `Boleto` com os dados necessários.
```php
$boleto = new Boleto(
    new ConvenioCobranca(...),
    new Documento(...),
    new Emissao(...),
    new Pagador(...),
    new Vencimento(...),
    new Instrucoes(...),
    new ValorBoleto(...),
    new AvisoSms(...),
    new PagamentoDivergente(...),
    new Avalista(...),
    1
);

$service->getGerarBoleto($convenio, $boleto);
```

#### Consultar Boleto
Consulta pelo convênio e número do boleto:
```php
$service->getConsultarBoleto($convenio, $numeroBoleto);
```

#### Gerar Lote de Boletos
```php
$service->getGerarBoletos($convenio, new ConvenioCobranca(...), $boleto);
```

#### Consultar Retorno do Lote de Boletos
Consulta pelo convênio e ticket:
```php
$service->getConsultarBoletos($convênio, $ticket);
```

#### Gerar Boleto - Carnê
Monte um objeto `Carne` com os dados necessários.
```php
$carne = new Carne(
    new ConvenioCobranca(...),
    new Documento(...),
    new Emissao(...),
    new Pagador(...),
    new Vencimento(...),
    new Instrucoes(...),
    new ValorBoleto(...),
    new AvisoSms(...),
    new PagamentoDivergente(...),
    new Avalista(...),
    1,
    1,
    new TipoVencimento(...)
);

$service->getGerarCarne($carne);
```
#### Gerar Lote de Boletos - Carnê
```php
$service->getGerarCarnes($convenio, new ConvenioCobranca(...), $carnes);
```

#### Consultar Retorno do Lote de Boletos - Carnê
Consulta pelo convênio e ticket:
```php
$service->getConsultarCarnes($convenio, $ticket);
```

### Arq. Retorno
#### Gerar Ticket
```php
$service->getTicketArqRetorno($convenio, $data);
```

#### Baixar Arq. Retorno
```php
$service->getArqRetorno($convenio, $ticket);
```

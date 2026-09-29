# Ailos Software Development Kit (SDK)
Este SDK foi desenvolvido para facilitar a integração com os serviços das cooperativas Ailos, oferecendo uma interface simples, segura e eficiente para desenvolvedores PHP.

<img width="1280" height="640" alt="screwdriver-wrench-solid-full 1 (1)" src="https://github.com/user-attachments/assets/3ef12722-d94e-487f-84a3-db0e3bb26def" />

<div align="center">

[![Stars](https://img.shields.io/github/stars/senna73/ailos-sdk?style=flat)](https://github.com/senna73/ailos-sdk)
[![Forks](https://img.shields.io/github/forks/senna73/ailos-sdk?style=flat)](https://github.com/senna73/ailos-sdk/network/members)
[![Release](https://img.shields.io/github/v/release/senna73/ailos-sdk?style=flat)](https://github.com/senna73/ailos-sdk/releases)
[![Release Date](https://img.shields.io/github/release-date/senna73/ailos-sdk?style=flat)](https://github.com/senna73/ailos-sdk/releases)
[![Last Commit](https://img.shields.io/github/last-commit/senna73/ailos-sdk?style=flat)](https://github.com/senna73/ailos-sdk)
[![Commit Activity](https://img.shields.io/github/commit-activity/y/senna73/ailos-sdk?style=flat)](https://github.com/senna73/ailos-sdk)
[![Issues](https://img.shields.io/github/issues/senna73/ailos-sdk?style=flat)](https://github.com/senna73/ailos-sdk/issues)
[![Pull Requests](https://img.shields.io/github/issues-pr/senna73/ailos-sdk?style=flat)](https://github.com/senna73/ailos-sdk/pulls)
[![Contributors](https://img.shields.io/github/contributors/senna73/ailos-sdk?style=flat)](https://github.com/senna73/ailos-sdk/graphs/contributors)
[![Top Language](https://img.shields.io/github/languages/top/senna73/ailos-sdk?style=flat)](https://github.com/senna73/ailos-sdk)
[![Repository Size](https://img.shields.io/github/repo-size/senna73/ailos-sdk?style=flat)](https://github.com/senna73/ailos-sdk)
[![Code Size](https://img.shields.io/github/languages/code-size/senna73/ailos-sdk?style=flat)](https://github.com/senna73/ailos-sdk)
[![License](https://img.shields.io/github/license/senna73/ailos-sdk?style=flat)](https://github.com/senna73/ailos-sdk/blob/main/LICENSE)
<!--
[![Tests](https://img.shields.io/github/actions/workflow/status/senna73/ailos-sdk/tests.yml?style=flat)](https://github.com/senna73/ailos-sdk/actions)
[![Coverage](https://img.shields.io/codecov/c/github/senna73/ailos-sdk?style=flat)](https://codecov.io/gh/senna73/ailos-sdk)
-->

</div>


## Instalação

```bash
composer require ailos/sdk
```

**Requisitos**
- PHP `^8.5`
- PHP extensão `ext-apcu`
- PHP extensão `ext-curl`
- PHP extensão `ext-dom`
- PHP extensão `ext-libxml`

## Cobrança

As primeiras funcionalidades que podem ser testadas utilizando a biblioteca são as funcionalidades da API de cobrança da Ailos. Essa API é responsável por controlar a emissão de boletos.

### Configuração

Para iniciar a biblioteca e começar a utilizar os serviços, basta configurar e chamar:

```php
$config = new CobrancaConfig(
    consumerKey: '...',
    consumerSecret: '...',
    urlCallback: 'https://meusite.com/callback',
    developerKey: '...',
    codigoCooperativa: '0101',
    codigoConta: '12345',
    senha: '...',
    ambiente: Ambiente::Producao,
);

$ailos = new Ailos()->cobranca($config);
```

Além das configurações padrões de credenciais e ambiente, a `CobrancaConfig` permite injetar suas próprias implementações para duas responsabilidades internas da biblioteca:

**`IStorage`**: responsável por armazenar as credenciais (tokens, etc). Por padrão, a biblioteca utiliza uma implementação baseada em [APCu](https://www.php.net/manual/pt_BR/book.apcu.php), mas você pode fornecer sua própria implementação, por exemplo, para persistir os dados em um banco de dados ou em um cache como Redis.

**`IHttp`**: responsável por realizar as chamadas HTTP à API da cooperativa. Por padrão, a biblioteca utiliza uma implementação baseada em cURL, mas você pode substituí-la pela biblioteca HTTP de sua preferência (Guzzle, Symfony HttpClient, etc).

Para utilizar suas próprias implementações, basta que elas implementem as interfaces `IStorage` e `IHttp` e sejam passadas ao construtor de `CobrancaConfig`:

```php
$config = new CobrancaConfig(
    storage: new RedisStorage(...),
    http: new GuzzleHttp(...),
);
```

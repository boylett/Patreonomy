# Patreonomy
Patreonomy is a PHP library for Patreon's v2 API. It works with both the regular API and OAuth API and covers as much of the documented interface as possible.

## Requirements
Patreonomy requires 64-bit PHP 8.0.0 or later.

## Documentation
Raw documentation is available via phpDocumentor in the `/docs` directory or viewable at [rytoonist.github.io/Patreonomy](https://rytoonist.github.io/Patreonomy/).

I'm actively working on a github wiki for the project.

## Getting Started
1. Install the library with [Composer](https://getcomposer.org):

```composer require pateronomy/patreonomy```

2. Gather your [Client Config](https://www.patreon.com/portal/registration/register-clients)[^client_api_version] and create a connection:

[^client_api_version]:
    Remember to select "API Version 2" when creating a new client

```php
<?php

    require_once __DIR__ . "/vendor/autoload.php";
    
    $patreon = new \Patreonomy\Patreonomy();

    $patreon->connect(
        client_id:     "5tgbZc3zXqR8vDZlflvqm_m4Ws-beNVnVE9NJeXpVYb_ZPIYeVg4Xt3biyMAjSdD",
        client_secret: "LTvtvoXuGba6ghobOvVO1dn5hgYIK0R4TnWAnhri-4dSYz1LygXgbFuevdViPvo8",
        access_token:  "-2Yynq6at1rDm9E_rxPdG-TyB0PqxIexPe0X61KG6WI",
        refresh_token: "m4mSnvXePFsDLTHBjqAG2HH2PgdOhBevAs-a3Jsi9yI",
    );
```

3. Do something:

```php
    \var_dump($patreon->getIdentity());
    
    /**
     * object(Patreonomy\Resource\User)#1 (18) {
     *   ...
     * }
     **/
    
    \var_dump($patreon->getCampaigns());
    
    /**
     * array(1) {
     *   [0]=>
     *   object(Patreonomy\Resource\Campaign)#1 (37) {
     *     ...
     *   }
     * }
     **/
```

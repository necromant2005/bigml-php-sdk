bigml-php-sdk
=============

[![Build Status](https://drone.io/github.com/necromant2005/bigml-php-sdk/status.png)](https://drone.io/github.com/necromant2005/bigml-php-sdk/latest)

Introduction
------------

BigML PHP SDK for bigml.com api access

Features / Goals
----------------

* Simple API access from php
* Cover data transfromation json => array , array=>json and error handling
* Implemntation resources: source, dataset, model, prediction, evaluation

Installation
------------

### Main Setup

#### With composer

1. Add this to your composer.json:

```json
"require": {
    "necromant2005/bigml-php-sdk": "dev-master",
}
```

2. Now tell composer to download BigMl PHP SDK by running the command:

```bash
$ php composer.phar update
```

#### Usage

Creating source resource in dev mode with API version "andromeda" with provided tokens
```php
use BigMl\Client\BigMl;

$source = BigMl::factory('source', array(
    'username' => 'alfred',
    'api_key'  => '79138a622755a2383660347f895444b1eb927730',
));
```

Creating source resource in production mode
```php
use BigMl\Client\BigMl;

$source = BigMl::factory('source', array(
    'username' => 'alfred',
    'api_key'  => '79138a622755a2383660347f895444b1eb927730',
    'mode' => null,
));
```

Creating source resource in production mode with API version "andromeda"
```php
use BigMl\Client\BigMl;

$source = BigMl::factory('source', array(
    'username' => 'alfred',
    'api_key'  => '79138a622755a2383660347f895444b1eb927730',
    'mode' => null,
    'version' => 'andromeda',
));
```
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
    "necromant2005/bigml-php-sdk": "1.*",
}
```

2. Now tell composer to download BigMl PHP SDK by running the command:

```bash
$ php composer.phar update
```

#### Usage

Creating source resource with API version "andromeda"
```php
use BigMl\Client\BigMl;

$source = BigMl::factory('source', array(
    'username' => 'alfred',
    'api_key'  => '79138a622755a2383660347f895444b1eb927730',
));
```

Creating source resource in develoment mode with specific api version
```php
use BigMl\Client\BigMl;

$source = BigMl::factory('source', array(
    'username' => 'alfred',
    'api_key'  => '79138a622755a2383660347f895444b1eb927730',
    'access_point' => 'https://bigml.io/dev/',
    'version' => 'andromeda',
));
```

#### Usage Basic

Creating resource through factory
```php
use BigMl\Client\BigMl;

BigMl::factory('source', array( ... )); // source
BigMl::factory('dataset', array( ... )); // dataset
BigMl::factory('model', array( ... )); // model
BigMl::factory('prediction', array( ... )); // prediction
BigMl::factory('evaluation', array( ... )); // evaluation
```

#### Usage Source
Create source data
```php
use BigMl\Client\BigMl;

$source = BigMl::factory('source', array( ... ));
$source->create(array('data' => array(
    'a', 'b', 'c',
    1, 2, 3,
    4, 5, 7
)));
```

Create source remote
```php
use BigMl\Client\BigMl;

$source = BigMl::factory('source', array( ... ));
$source->create(array('remote' => 's3://bigml-public/csv/iris.csv'));
```

Get info about source
```php
use BigMl\Client\BigMl;

$source = BigMl::factory('source', array( ... ));
$source->retrieve('source/4f510d2003ce895676000069');
```

Get info with waiting til the process is finished and checking status every 10 seconds
```php
use BigMl\Client\BigMl;

$source = BigMl::factory('source', array( ... ));
$source->wait('source/4f510d2003ce895676000069', 10);
```

Find source with name 'iris'
```php
use BigMl\Client\BigMl;

$source = BigMl::factory('source', array( ... ));
$source->retrieve('source', array(
    'name' => 'iris'
));
```

Remove sorce
```php
use BigMl\Client\BigMl;

$source = BigMl::factory('source', array( ... ));
$source->delete('source/4f510d2003ce895676000069');
```

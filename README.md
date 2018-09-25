# Google AutoComplemte
[![Build Status](https://travis-ci.org/wearesho-team/google-autocomplete.svg?branch=master)](https://travis-ci.org/wearesho-team/google-autocomplete)
[![codecov](https://codecov.io/gh/wearesho-team/google-autocomplete/branch/master/graph/badge.svg)](https://codecov.io/gh/wearesho-team/google-autocomplete)

This library allows you to search Ukrainian cities/streets using google api.

## Installation

```bash
composer require wearesho-team/google-autocomplete
```

## Usage

Create configuration

```php
<?php

$config = new \Wearesho\GoogleAutocomplete\Config(
    $apiKey = 'your personal api key',
    $requestUrl = 'https://google.com/' // optional
);
```

Or use [Environment Config](./src/EnvironmentConfig.php) to load variables from environment

| Variable | Required | Default value | Description |
|:-------------------------------:|:--------:|:------------------------------------------------------------:|:---------------------------------------:|
| GOOGLE_SERVICE_AUTOCOMPLETE_URL | no | https://maps.googleapis.com/maps/api/place/autocomplete/json | url for google-autocomplete-api service |
| GOOGLE_SERVICE_AUTOCOMPLETE_KEY | yes |  | your private key |

```php
<?php

$config = new \Wearesho\GoogleAutocomplete\EnvironmentConfig('GOOGLE_SERVICE_AUTOCOMPLETE');
```

### Create service

```php
<?php

/** @var \Wearesho\GoogleAutocomplete\ConfigInterface $config */

$service = new \Wearesho\GoogleAutocomplete\Service(
    $config,
    new \GuzzleHttp\Client()
);

```

### Create search data entity

#### Session token

A random string which identifies an autocomplete session for billing purposes for user
In google-docs sad if this parameter is omitted from an autocomplete request, the request is billed independently. 
So this service is binding to use it.

*Recommended to use hash string*.

```php
<?php

$token = 'any_random_string';
```

#### Searching cities
```php
<?php

use Wearesho\GoogleAutocomplete;

$searchQuery = new GoogleAutocomplete\Queries\CitySearch(
    $token,
    'Value from input',
    $language = GoogleAutocomplete\Enums\SearchLanguage::RU(),
    GoogleAutocomplete\Enums\SearchMode::SHORT() // optional
);
```

#### Searching streets
```php
<?php

use Wearesho\GoogleAutocomplete;

$searchQuery = new GoogleAutocomplete\Queries\StreetSearch(
    $token,
    'Value from input',
    $language = GoogleAutocomplete\Enums\SearchLanguage::RU(), // optional
    $city = 'city name',
    $type = 'avenue', // optional
    GoogleAutocomplete\Enums\SearchMode::SHORT() // optional
);
```

### Receive suggestions

```php
<?php

/** @var \Wearesho\GoogleAutocomplete\Service $service */
/** @var \Wearesho\GoogleAutocomplete\Queries\Interfaces\SearchQueryInterface $searchQuery */

$service->setParameters($searchQuery); // Set parameters for service
$service->load(); // invoke query
$suggestions = $service->getResults(); // get collection object of locations

$values = $suggestions->jsonSerialize();
```

## Yii2 configuration

If you need to configure your yii2 application, you can use [Bootstrap](./src/Yii/Bootstrap.php)

```php
<?php

// config/main.php

return [
    'bootstrap' => [
        'class' => \Wearesho\GoogleAutocomplete\Yii\Bootstrap::class,        
    ],
];
```

## Authors

- [Alexander Yagich](mailto:aleksa.yagich@gmail.com)
- [Roman Varkuta](mailto:roman.varkuta@gmail.com)

## License

- [MIT](./LICENSE)

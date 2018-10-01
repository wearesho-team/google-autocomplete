# Google API Autocomplete Integration
[![Build Status](https://travis-ci.org/wearesho-team/google-autocomplete.svg?branch=master)](https://travis-ci.org/wearesho-team/google-autocomplete)
[![codecov](https://codecov.io/gh/wearesho-team/google-autocomplete/branch/master/graph/badge.svg)](https://codecov.io/gh/wearesho-team/google-autocomplete)

This library allows you to search cities/streets of concrete country using google api.

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
    $country = 'ua', // optional
    $requestUrl = 'https://google.com/' // optional
);
```

Or use [Environment Config](./src/EnvironmentConfig.php) to load variables from environment

| Variable | Required | Default value | Description |
|:-----------------------------------:|:--------:|:------------------------------------------------------------:|:---------------------------------------:|
| GOOGLE_SERVICE_AUTOCOMPLETE_URL | no | https://maps.googleapis.com/maps/api/place/autocomplete/json | url for google-autocomplete-api service |
| GOOGLE_SERVICE_AUTOCOMPLETE_COUNTRY | no | ua | ISO alpha-2 country code in lower case |
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
    $mode = GoogleAutocomplete\Enums\SearchMode::SHORT() // optional
);
```

#### Searching streets
```php
<?php

use Wearesho\GoogleAutocomplete;

$searchQuery = new GoogleAutocomplete\Queries\StreetSearch(
    $token,
    'Value from input',
    $language = GoogleAutocomplete\Enums\SearchLanguage::RU(),
    $city = 'city name', // optional
    $type = 'avenue', // optional
    $mode = GoogleAutocomplete\Enums\SearchMode::SHORT() // optional
);
```

If you want to customize your queries use [Query Interfaces](./src/Queries/Interfaces)

### Receive suggestions

```php
<?php

/** @var \Wearesho\GoogleAutocomplete\Service $service */
/** @var \Wearesho\GoogleAutocomplete\Queries\Interfaces\SearchQueryInterface $searchQuery */

$service->load($searchQuery); // invoke query
$suggestions = $service->getResults(); // get collection object of locations

// or use it fluent
$suggestions = $service->load($searchQuery)->getResults();

$values = $suggestions->jsonSerialize();
```

### Search mode

Service have [2 modes](./src/Enums/SearchMode.php) for searching results:

```php
<?php

use Wearesho\GoogleAutocomplete\Enums\SearchMode;

/**
 * This mode provide service for returning short names of locations
 * 
 * @example "Paris"
 *          "Kharkov"
 */
$mode = SearchMode::SHORT();

/**
 * This mode provide service for returning full names of locations
 * 
 * @example "Paris, France"
 *          "Kharkov, Kharkiv Oblast, Ukraine"
 */
$mode = SearchMode::FULL();
```

This parameter is optional.
By default set FULL() mode.

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

## Contributing

To contribute you need to fork this repo and than create a pull request

### Search language

Add language code to [SearchLanguage.php](./src/Enums/SearchLanguage.php)

```php
<?php

namespace Wearesho\GoogleAutocomplete\Enums;

use MyCLabs\Enum\Enum;

/**
 * Interface SearchLanguage
 * @package Wearesho\GoogleAutocomplete\Enums
 *
 * @method static static RU()
 * @method static static UK()
 *          
 * @method static static YOUR_LANGUAGE_KEY()
 */
final class SearchLanguage extends Enum
{
    protected const RU = 'ru';
    protected const UK = 'uk';
    
    protected const YOUR_LANGUAGE_KEY = 'language_code';
}
```

### Frameworks configurations

Add directory with name of needs framework to [source directory](./src) and create your Bootstrap.php file

## Authors

- [Alexander Yagich](mailto:aleksa.yagich@gmail.com)
- [Roman Varkuta](mailto:roman.varkuta@gmail.com)

## License

- [MIT](./LICENSE)

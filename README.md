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

#### Searching cities
```php
<?php

use Wearesho\GoogleAutocomplete;

$searchQuery = new GoogleAutocomplete\SearchQuery(
    'Value from input',
    $addressType = GoogleAutocomplete\Enums\AddressPart::CITY(),
    $language = GoogleAutocomplete\Enums\SearchLanguage::RU()
);
```

#### Searching streets
```php
<?php

use Wearesho\GoogleAutocomplete;

// all streets
$searchQuery = new GoogleAutocomplete\SearchQuery(
    'Value from input',
    $addressType = GoogleAutocomplete\Enums\AddressPart::STREET(),
    $language = GoogleAutocomplete\Enums\SearchLanguage::RU()
);

// Safe searching streets in concrete city
$searchQuery = new GoogleAutocomplete\SearchQuery(
    'Value from input',
    $addressType = GoogleAutocomplete\Enums\AddressPart::STREET(),
    $language = GoogleAutocomplete\Enums\SearchLanguage::RU(),
    $city = 'city name'
);
```

### Receive suggestions

```php
<?php

/** @var \Wearesho\GoogleAutocomplete\Service $service */
/** @var \Wearesho\GoogleAutocomplete\SearchQueryInterface $searchQuery */

$suggestions = $service->load($searchQuery);
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

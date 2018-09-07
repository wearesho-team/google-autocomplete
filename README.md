#Google autocomplete
This library allows you to search cities/streets using google api.

## Installation

```bash
composer require wearesho-team/google-autocomplete
```

## Usage

Create configuration

```php
<?php

$config = new \Wearesho\GoogleAutocomplete\Config(
    $requestUrl = 'https://google.com/',
    $apiKey = 'your personal api key'
);
```

Or use [Environment Config](./src/EnvironmentConfig.php) to load variables from environment

|             Variable            | Required |               Description               |
|:-------------------------------:|:--------:|:---------------------------------------:|
| GOOGLE_SERVICE_AUTOCOMPLETE_URL | yes      | url for google-autocomplete-api service |
| GOOGLE_SERVICE_AUTOCOMPLETE_KEY | yes      | your private key                        |

```php
<?php

$config = new \Wearesho\GoogleAutocomplete\EnvironmentConfig('GOOGLE_SERVICE_AUTOCOMPLETE');
```

Create service

```php
<?php

/** @var \Wearesho\GoogleAutocomplete\ConfigInterface $config */

$service = new \Wearesho\GoogleAutocomplete\Service(
    $config,
    new \GuzzleHttp\Client()
);

```

Create search data entity

```php
<?php

use Wearesho\GoogleAutocomplete;

$searchQuery = new GoogleAutocomplete\SearchQuery(
    'Value from input',
    $addressType = GoogleAutocomplete\Enums\AddressPart::STREET(),
    $language = GoogleAutocomplete\Enums\SearchLanguage::RU(),
    $city = 'city name' // optional
);
```

Receive suggestions

```php
<?php

/** @var \Wearesho\GoogleAutocomplete\Service $service */
/** @var \Wearesho\GoogleAutocomplete\SearchQueryInterface $searchData */

$suggestions = $service->load($searchData);
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

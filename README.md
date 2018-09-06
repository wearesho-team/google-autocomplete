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

```dotenv
GOOGLE_SERVICE_AUTOCOMPLETE_URL=https://google.com/
GOOGLE_SERVICE_AUTOCOMPLETE_KEY=yourApiKey
```

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

$searchData = new \Wearesho\GoogleAutocomplete\SearchData(
    'Value from input',
    $addressType = \Wearesho\GoogleAutocomplete\Type::STREET,
    $language = \Wearesho\GoogleAutocomplete\Language::RU
);
```

Receive suggestions

```php
<?php

/** @var \Wearesho\GoogleAutocomplete\Service $service */
/** @var \Wearesho\GoogleAutocomplete\SearchDataInterface $searchData */

$suggestions = $service->load($searchData);
```

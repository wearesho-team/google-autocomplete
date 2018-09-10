<?php

namespace Wearesho\GoogleAutocomplete;

use Horat1us\Environment;

/**
 * Class EnvironmentConfig
 * @package Wearesho\GoogleAutocomplete
 */
class EnvironmentConfig extends Environment\Config implements ConfigInterface
{
    public function __construct(string $keyPrefix = 'GOOGLE_SERVICE_AUTOCOMPLETE_')
    {
        parent::__construct($keyPrefix);
    }

    public function getUrl(): string
    {
        return $this->getEnv('URL', 'https://maps.googleapis.com/maps/api/place/autocomplete/json');
    }

    public function getKey(): string
    {
        return $this->getEnv('KEY');
    }
}

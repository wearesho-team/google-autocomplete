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
        return $this->getEnv('URL', ConfigInterface::URL);
    }

    public function getKey(): string
    {
        return $this->getEnv('KEY');
    }

    public function getCountry(): string
    {
        return $this->getEnv('COUNTRY', ConfigInterface::UKRAINE);
    }
}

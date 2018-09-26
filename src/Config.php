<?php

namespace Wearesho\GoogleAutocomplete;

/**
 * Class Config
 * @package Wearesho\GoogleAutocomplete
 */
class Config implements ConfigInterface
{
    /** @var string */
    protected $url;

    /** @var string */
    protected $key;

    /** @var string */
    protected $country;

    public function __construct(
        string $key,
        string $country = ConfigInterface::UKRAINE,
        string $url = ConfigInterface::URL
    ) {
        $this->url = $url;
        $this->key = $key;
        $this->country = $country;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getCountry(): string
    {
        return $this->country;
    }
}

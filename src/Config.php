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

    public function __construct(string $url, string $key)
    {
        $this->url = $url;
        $this->key = $key;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getKey(): string
    {
        return $this->key;
    }
}

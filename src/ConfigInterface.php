<?php

namespace Wearesho\GoogleAutocomplete;

/**
 * Interface ConfigInterface
 * @package Wearesho\GoogleAutocomplete
 */
interface ConfigInterface
{
    public const UKRAINE = 'ua';
    public const URL = 'https://maps.googleapis.com/maps/api/place/autocomplete/json';

    public function getUrl(): string;

    /**
     * Project key that set to you by Google Cloud
     *
     * @return string
     */
    public function getKey(): string;
}

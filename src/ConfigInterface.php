<?php

namespace Wearesho\GoogleAutocomplete;

/**
 * Interface ConfigInterface
 * @package Wearesho\GoogleAutocomplete
 */
interface ConfigInterface
{
    public const UKRAINE_CODE = 'ua';

    public function getUrl(): string;

    public function getKey(): string;
}

<?php

namespace Wearesho\GoogleAutocomplete;

/**
 * Interface SearchDataInterface
 * @package Wearesho\GoogleAutocomplete
 */
interface SearchDataInterface
{
    public function getInput(): string;

    public function getLanguage(): string;

    public function getType(): string;
}

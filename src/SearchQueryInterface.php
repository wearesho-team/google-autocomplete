<?php

namespace Wearesho\GoogleAutocomplete;

use Wearesho\GoogleAutocomplete\Enums;

/**
 * Interface SearchQueryInterface
 * @package Wearesho\GoogleAutocomplete
 */
interface SearchQueryInterface
{
    public function getInput(): string;

    public function getLanguage(): Enums\SearchLanguage;

    public function getType(): Enums\AddressPart;

    public function getCity(): ?string;
}

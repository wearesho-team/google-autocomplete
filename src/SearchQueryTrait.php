<?php

namespace Wearesho\GoogleAutocomplete;

use Wearesho\GoogleAutocomplete\Enums;

/**
 * Trait SearchQueryTrait
 * @package Wearesho\GoogleAutocomplete
 */
trait SearchQueryTrait
{
    /** @var string */
    protected $input;

    /** @var Enums\AddressPart */
    protected $type;

    /** @var Enums\SearchLanguage */
    protected $language;

    /** @var string|null */
    protected $city;

    public function getInput(): string
    {
        return $this->input;
    }

    public function getType(): Enums\AddressPart
    {
        return $this->type;
    }

    public function getLanguage(): Enums\SearchLanguage
    {
        return $this->language;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }
}

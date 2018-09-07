<?php

namespace Wearesho\GoogleAutocomplete;

/**
 * Class Location
 * @package Wearesho\GoogleAutocomplete
 */
class Location
{
    /** @var string */
    protected $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}

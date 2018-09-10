<?php

namespace Wearesho\GoogleAutocomplete;

/**
 * Class Location
 * @package Wearesho\GoogleAutocomplete
 */
class Location implements \JsonSerializable
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

    public function __toString(): string
    {
        return $this->value;
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}

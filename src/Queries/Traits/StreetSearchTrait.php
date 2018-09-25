<?php

namespace Wearesho\GoogleAutocomplete\Queries\Traits;

/**
 * Trait StreetSearchTrait
 * @package Wearesho\GoogleAutocomplete\Queries\Traits
 */
trait StreetSearchTrait
{
    /** @var string */
    protected $city;

    /** @var string */
    protected $type;

    /**
     * @inheritdoc
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @inheritdoc
     */
    public function getType(): ?string
    {
        return $this->type;
    }
}

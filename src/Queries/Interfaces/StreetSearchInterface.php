<?php

namespace Wearesho\GoogleAutocomplete\Queries\Interfaces;

/**
 * Interface StreetSearchInterface
 * @package Wearesho\GoogleAutocomplete\Queries\Interfaces
 */
interface StreetSearchInterface extends SearchQueryInterface
{
    /**
     * Type of street that you want load for autocomplete into form
     *
     * @example 'avenue'
     * @example 'провулок'
     * @example 'набережная'
     *
     * @return string
     */
    public function getType(): ?string;

    /**
     * Optional parameter. City where address must be search
     * It is used not in the search itself, but when filtering the downloaded results
     *
     * @return string
     */
    public function getCity(): ?string;
}

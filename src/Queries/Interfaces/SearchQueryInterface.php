<?php

namespace Wearesho\GoogleAutocomplete\Queries\Interfaces;

use Wearesho\GoogleAutocomplete\Enums;

/**
 * Interface SearchQueryInterface
 * @package Wearesho\GoogleAutocomplete\Queries\Interfaces
 */
interface SearchQueryInterface
{
    public const CITY = 'city';
    public const STREET = 'street';

    /**
     * A random string which identifies an autocomplete session for billing purposes
     *
     * @return string
     */
    public function getToken(): string;

    /**
     * The text string on which to search
     *
     * @return string
     */
    public function getInput(): string;

    /**
     * The language code, indicating in which language the results should be returned, if possible
     *
     * @return Enums\SearchLanguage
     */
    public function getLanguage(): Enums\SearchLanguage;

    /**
     * Mode in whish format should return result: full naming or short
     *
     * @example in short mode: Paris
     *          in full: Paris, France
     *
     * @return Enums\SearchMode
     */
    public function getMode(): Enums\SearchMode;
}

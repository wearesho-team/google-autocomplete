<?php

namespace Wearesho\GoogleAutocomplete;

use Wearesho\GoogleAutocomplete\Enums;

/**
 * Class SearchQuery
 * @package Wearesho\GoogleAutocomplete
 */
class SearchQuery implements SearchQueryInterface
{
    use SearchQueryTrait;

    public function __construct(
        string $input,
        Enums\AddressPart $addressPart,
        Enums\SearchLanguage $searchLanguage,
        ?string $city = null
    ) {
        $this->input = $input;
        $this->type = $addressPart;
        $this->language = $searchLanguage;
        $this->city = $city;
    }
}

<?php

namespace Wearesho\GoogleAutocomplete\Queries;

use Wearesho\GoogleAutocomplete\Enums;

/**
 * Class StreetSearch
 * @package Wearesho\GoogleAutocomplete\Queries
 */
class StreetSearch extends SearchQuery implements Interfaces\StreetSearchInterface
{
    use Traits\StreetSearchTrait;

    public function __construct(
        string $token,
        string $input,
        Enums\SearchLanguage $searchLanguage,
        string $city = null,
        string $type = null,
        Enums\SearchMode $mode = null
    ) {
        $this->city = $city;
        $this->type = $type;

        parent::__construct($token, $input, $searchLanguage, $mode);
    }
}

<?php

namespace Wearesho\GoogleAutocomplete\Queries;

use Wearesho\GoogleAutocomplete\Enums;

/**
 * Class SearchQuery
 * @package Wearesho\GoogleAutocomplete\Queries
 */
abstract class SearchQuery implements Interfaces\SearchQueryInterface
{
    use Traits\SearchQueryTrait;

    public function __construct(
        string $token,
        string $input,
        Enums\SearchLanguage $searchLanguage,
        Enums\SearchMode $mode = null
    ) {
        $this->token = $token;
        $this->input = $input;
        $this->language = $searchLanguage;
        $this->mode = $mode ?? Enums\SearchMode::FULL();
    }
}

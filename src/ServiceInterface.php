<?php

namespace Wearesho\GoogleAutocomplete;

/**
 * Interface ServiceInterface
 * @package Wearesho\GoogleAutocomplete
 */
interface ServiceInterface
{
    public function load(SearchQueryInterface $query): LocationCollection;
}

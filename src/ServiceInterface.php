<?php

namespace Wearesho\GoogleAutocomplete;

use Wearesho\GoogleAutocomplete\Queries\Interfaces\SearchQueryInterface;

/**
 * Interface ServiceInterface
 * @package Wearesho\GoogleAutocomplete
 */
interface ServiceInterface
{
    public function load(): ServiceInterface;

    public function getResults(): LocationCollection;

    public function setParameters(SearchQueryInterface $query): ServiceInterface;
}

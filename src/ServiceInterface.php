<?php

namespace Wearesho\GoogleAutocomplete;

/**
 * Interface ServiceInterface
 * @package Wearesho\GoogleAutocomplete
 */
interface ServiceInterface
{
    public function load(Queries\Interfaces\SearchQueryInterface $query): ServiceInterface;

    public function getResults(): LocationCollection;
}

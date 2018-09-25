<?php

namespace Wearesho\GoogleAutocomplete\Exceptions;

use Wearesho\GoogleAutocomplete\Queries\Interfaces\SearchQueryInterface;

/**
 * Class InvalidQueryType
 * @package Wearesho\GoogleAutocomplete\Exceptions
 */
class InvalidQueryType extends \InvalidArgumentException
{
    /** @var SearchQueryInterface */
    protected $query;

    public function __construct(
        SearchQueryInterface $query,
        int $code = 0,
        \Throwable $previous = null
    ) {
        $this->query = $query;

        parent::__construct("Invalid query type", $code, $previous);
    }

    public function getQuery(): SearchQueryInterface
    {
        return $this->query;
    }
}

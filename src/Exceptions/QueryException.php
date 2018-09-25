<?php

namespace Wearesho\GoogleAutocomplete\Exceptions;

use Wearesho\GoogleAutocomplete\Enums\SearchStatus;
use Wearesho\GoogleAutocomplete\Queries\Interfaces\SearchQueryInterface;

/**
 * Class RequestException
 * @package Wearesho\GoogleAutocomplete\Exceptions
 */
class QueryException extends \Exception
{
    /** @var SearchQueryInterface|null */
    protected $query;

    /** @var SearchStatus */
    protected $status;

    public function __construct(
        ?SearchQueryInterface $query,
        SearchStatus $status,
        int $code = 0,
        \Throwable $previous = null
    ) {
        $this->query = $query;
        $this->status = $status;

        parent::__construct("Search failed with status [{$status->getValue()}]", $code, $previous);
    }

    public function getQuery(): ?SearchQueryInterface
    {
        return $this->query;
    }

    public function getStatus(): SearchStatus
    {
        return $this->status;
    }
}

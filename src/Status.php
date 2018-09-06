<?php

namespace Wearesho\GoogleAutocomplete;

/**
 * Interface Status
 * @package Wearesho\GoogleAutocomplete
 */
interface Status
{
    public const OK = 'OK';
    public const ZERO_RESULTS = 'ZERO_RESULTS';
    public const OVER_QUERY_LIMIT = 'OVER_QUERY_LIMIT';
    public const REQUEST_DENIED = 'REQUEST_DENIED';
    public const INVALID_REQUEST = 'INVALID_REQUEST';
    public const UNKNOWN_ERROR = 'UNKNOWN_ERROR';
}

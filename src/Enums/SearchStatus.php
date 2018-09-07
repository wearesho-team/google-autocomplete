<?php

namespace Wearesho\GoogleAutocomplete\Enums;

use MyCLabs\Enum\Enum;

/**
 * Class SearchStatus
 * @package Wearesho\GoogleAutocomplete\Enums
 *
 * @method static static OK()
 * @method static static ZERO_RESULTS()
 * @method static static OVER_QUERY_LIMIT()
 * @method static static REQUEST_DENIED()
 * @method static static INVALID_REQUEST()
 * @method static static UNKNOWN_ERROR()
 */
final class SearchStatus extends Enum
{
    protected const OK = 'OK';
    protected const ZERO_RESULTS = 'ZERO_RESULTS';
    protected const OVER_QUERY_LIMIT = 'OVER_QUERY_LIMIT';
    protected const REQUEST_DENIED = 'REQUEST_DENIED';
    protected const INVALID_REQUEST = 'INVALID_REQUEST';
    protected const UNKNOWN_ERROR = 'UNKNOWN_ERROR';
}

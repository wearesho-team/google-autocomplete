<?php

namespace Wearesho\GoogleAutocomplete\Enums;

use MyCLabs\Enum\Enum;

/**
 * Class SearchStatus
 * @package Wearesho\GoogleAutocomplete\Enums
 *
 * @method static SearchStatus OK()
 * @method static SearchStatus ZERO_RESULTS()
 * @method static SearchStatus OVER_QUERY_LIMIT()
 * @method static SearchStatus REQUEST_DENIED()
 * @method static SearchStatus INVALID_REQUEST()
 * @method static SearchStatus UNKNOWN_ERROR()
 */
final class SearchStatus extends Enum
{
    public const OK = 'OK';
    public const ZERO_RESULTS = 'ZERO_RESULTS';
    public const OVER_QUERY_LIMIT = 'OVER_QUERY_LIMIT';
    public const REQUEST_DENIED = 'REQUEST_DENIED';
    public const INVALID_REQUEST = 'INVALID_REQUEST';
    public const UNKNOWN_ERROR = 'UNKNOWN_ERROR';
}

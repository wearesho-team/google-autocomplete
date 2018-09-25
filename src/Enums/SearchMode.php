<?php

namespace Wearesho\GoogleAutocomplete\Enums;

use MyCLabs\Enum\Enum;

/**
 * Class SearchMode
 * @package Wearesho\GoogleAutocomplete\Enums
 *
 * @method static SearchMode SHORT()
 * @method static SearchMode FULL()
 */
final class SearchMode extends Enum
{
    protected const SHORT = 0;
    protected const FULL = 1;
}

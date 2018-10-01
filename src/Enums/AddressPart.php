<?php

namespace Wearesho\GoogleAutocomplete\Enums;

use MyCLabs\Enum\Enum;

/**
 * Class AddressPart
 * @package Wearesho\GoogleAutocomplete\Enums
 *
 * @method static static STREET()
 * @method static static CITY()
 *
 * @deprecated
 */
final class AddressPart extends Enum
{
    protected const STREET = 'street';
    protected const CITY = 'city';
}

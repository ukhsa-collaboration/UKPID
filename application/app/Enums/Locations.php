<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum Locations
{
    use EnumToArray;

    case CARDIFF;

    case NEWCASTLE;

    case BIRMINGHAM;

    case EDINBURGH;
}

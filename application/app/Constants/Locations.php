<?php

namespace App\Constants;

use ReflectionClass;

class Locations
{
    public const CARDIFF = 1;

    public const NEWCASTLE = 2;

    public const BIRMINGHAM = 3;

    public const EDINBURGH = 4;

    public static function all()
    {
        $reflectionClass = new ReflectionClass(self::class);

        return $reflectionClass->getConstants();
    }
}

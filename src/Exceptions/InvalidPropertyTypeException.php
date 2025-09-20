<?php

namespace BetterWorld\Scribe\Exceptions;

use Exception;

class InvalidPropertyTypeException extends Exception
{
    public static function make(string $property): InvalidDatetimeStringException
    {
        return new InvalidDatetimeStringException("[{$property}] uses an invalid property type.");
    }
}

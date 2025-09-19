<?php

namespace BetterWorld\Scribe\Exceptions;

use Exception;

class InvalidDatetimeStringException extends Exception
{
    public static function make(string $datetime, string $format): InvalidDatetimeStringException
    {
        return new InvalidDatetimeStringException("[{$datetime}] is not a {$format} datetime string.");
    }
}

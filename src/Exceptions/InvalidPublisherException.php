<?php

namespace BetterWorld\Scribe\Exceptions;

use BetterWorld\Scribe\Contracts\Publisher;
use Exception;

class InvalidPublisherException extends Exception
{
    public static function make(string $class): InvalidPublisherException
    {
        return new InvalidPublisherException("[$class] must implement ".Publisher::class.'.');
    }
}

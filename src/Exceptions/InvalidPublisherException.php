<?php

namespace Narrative\Exceptions;

use Exception;
use Narrative\Contracts\Publisher;

class InvalidPublisherException extends Exception
{
    public static function make(string $class): InvalidPublisherException
    {
        return new InvalidPublisherException("[$class] must implement ".Publisher::class);
    }
}

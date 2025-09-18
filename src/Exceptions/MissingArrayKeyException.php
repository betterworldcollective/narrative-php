<?php

namespace Narrative\Exceptions;

use Exception;

class MissingArrayKeyException extends Exception
{
    public static function make(string $key): MissingArrayKeyException
    {
        return new MissingArrayKeyException("Missing array key [$key]'.");
    }
}

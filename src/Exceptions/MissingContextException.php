<?php

namespace Narrative\Exceptions;

use Exception;

class MissingContextException extends Exception
{
    public static function make(): MissingContextException
    {
        return new MissingContextException('[Context] attribute is missing.');
    }
}

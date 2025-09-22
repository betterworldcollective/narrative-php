<?php

/**
 * THIS IS JUST AN EXAMPLE FILE. THIS IS NOT INTENDED TO BE USED IN YOUR APPLICATION.
 */

namespace BetterWorld\Scribe\Narratives\Scopes;

use BetterWorld\Scribe\Scope;

class UserScope extends Scope
{
    protected static array $books = ['main'];

    protected static string $context = 'The ID of the user who triggered the event.';
}

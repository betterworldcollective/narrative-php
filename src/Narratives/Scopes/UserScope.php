<?php

namespace BetterWorld\Scribe\Narratives\Scopes;

use BetterWorld\Scribe\Scope;

class UserScope extends Scope
{
    protected static array $books = ['main'];

    protected static string $context = 'The ID of the user who triggered the event.';
}

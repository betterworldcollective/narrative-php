<?php

namespace BetterWorld\Scribe\Narratives\Scopes;

use BetterWorld\Scribe\Scope;

class UserIdScope extends Scope
{
    protected static array $books = ['main'];

    protected static string $context = 'The ID of the user who triggered the event.';

    public function values(): array
    {
        return [
            ['id' => '1', 'name' => 'Juan Tamad'],
            ['id' => '2', 'name' => 'John Doe'],
            ['id' => '3', 'name' => 'Joe'],
            ['id' => '4', 'name' => 'Jane Doe'],
        ];
    }
}

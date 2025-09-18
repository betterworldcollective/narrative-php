<?php

namespace Narrative\Narratives\Scopes;

use Narrative\Contracts\Scope;

class UserIdScope implements Scope
{
    public static function key(): string
    {
        return 'user-id';
    }

    public static function name(): string
    {
        return 'User ID';
    }

    public static function context(): string
    {
        return 'This is the user the triggered the event.';
    }

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

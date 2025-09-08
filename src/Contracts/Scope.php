<?php

namespace Narrative\Contracts;

interface Scope
{
    /**
     * The storylines that the scope belongs to.
     *
     * @return string[]
     */
    public static function storylines(): array;

    public static function name(): string;

    public static function context(): string;

    /**
     * @return array{id:string, name:string}[]
     */
    public function values(): array;
}

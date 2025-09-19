<?php

namespace BetterWorld\Scribe\Contracts;

interface Scope
{
    public static function key(): string;

    public static function name(): string;

    public static function context(): string;

    /**
     * @return array{id:string, name:string}[]
     */
    public function values(): array;
}

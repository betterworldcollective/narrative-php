<?php

namespace BetterWorld\Scribe;

use BetterWorld\Scribe\Exceptions\MissingContextException;

use function BetterWorld\Scribe\Support\between;
use function BetterWorld\Scribe\Support\delimited_case;
use function BetterWorld\Scribe\Support\headline;

abstract class Scope
{
    protected static string $key;

    protected static string $label;

    protected static string $context;

    /** @var string[] */
    protected static array $books;

    final public function __construct(
        public string|int $id,
        public string $name
    ) {}

    public static function of(string|int $id, string $name): static
    {
        return new static($id, $name);
    }

    public static function key(): string
    {
        return static::$key ?? delimited_case(between(static::class, '\\'));
    }

    public static function label(): string
    {
        return static::$label ?? headline(between(static::class, '\\'));
    }

    public static function context(): string
    {
        return static::$context ?? throw MissingContextException::make();
    }

    /**
     * @return string[]
     */
    public static function books(): array
    {
        return static::$books;
    }
}

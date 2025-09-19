<?php

namespace BetterWorld\Scribe\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Books
{
    /** @var string[] */
    public array $books = [];

    public function __construct(string ...$books)
    {
        $this->books = $books;
    }
}

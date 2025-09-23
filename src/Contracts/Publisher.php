<?php

namespace BetterWorld\Scribe\Contracts;

interface Publisher
{
    /**
     * All publishers must adhere to this constructor fingerprint.
     *
     * @param  array<string,mixed>  $options
     */
    public function __construct(string $name, array $options = []);

    /**
     * Get the name of the Publisher.
     */
    public function name(): string;

    /**
     * Publish the given book.
     */
    public function publish(Book $book): bool;
}

<?php

namespace BetterWorld\Scribe\Contracts;

interface Book
{
    /**
     * Get the name of the book.
     */
    public function name(): string;

    /**
     * Write narratives into the book.
     */
    public function write(Narrative $narrative): static;

    /**
     * Read all the narratives written in the book.
     *
     * @return array<Narrative>
     */
    public function read(): array;

    /**
     * Get a list of all the book's publishers.
     *
     * @return Publisher[]
     */
    public function publishers(): array;

    /**
     * Publish the narratives into all the defined publishers.
     */
    public function publish(): void;
}

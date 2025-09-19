<?php

namespace BetterWorld\Scribe\Contracts;

use BetterWorld\Scribe\ScopedNarrative;

interface Book
{
    public function name(): string;

    public function write(Narrative|ScopedNarrative $narrative): static;

    /**
     * @return array<Narrative|ScopedNarrative>
     */
    public function read(): array;

    /** @return Publisher[] */
    public function publishers(): array;

    public function publish(): void;
}

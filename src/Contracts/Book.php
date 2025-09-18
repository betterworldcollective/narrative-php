<?php

namespace Narrative\Contracts;

use Narrative\ScopedNarrative;

interface Book
{
    public function write(Narrative|ScopedNarrative $narrative): static;

    /**
     * @return array<Narrative|ScopedNarrative>
     */
    public function read(): array;

    /** @return Publisher[] */
    public function publishers(): array;

    public function publish(): void;
}

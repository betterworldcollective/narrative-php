<?php

namespace Narrative\Contracts;

use Narrative\ScopedNarrative;

interface Book
{
    public function write(Narrative|ScopedNarrative $narrative): static;

    /**
     * @return array<Narrative|ScopedNarrative>
     */
    public function read(?string $storyline = null): array;

    /** @return string[] */
    public function storylines(): array;

    public function publish(Publisher $publisher): void;

    public function isPublished(): bool;
}

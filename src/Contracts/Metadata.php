<?php

namespace BetterWorld\Scribe\Contracts;

/**
 * Metadata are additional data you can attach on a narrative.
 * This can be useful for non-narrative publishers.
 */
interface Metadata
{
    /**
     * Sets the metadata on the narrative.
     *
     * @param  array<string,mixed>  $metadata
     */
    public function withMetadata(array $metadata): static;

    /**
     * Gets the metadata on the narrative.
     *
     * @return array<string,mixed>
     */
    public function metadata(): array;
}

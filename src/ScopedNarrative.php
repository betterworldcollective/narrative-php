<?php

namespace BetterWorld\Scribe;

use BetterWorld\Scribe\Contracts\Narrative;

class ScopedNarrative
{
    /**
     * NOTE: Narrative will only use array<string,string> type of scopes.
     *
     * @param  array<string,string|array<mixed>>  $scopes
     */
    public function __construct(public array $scopes, public Narrative $narrative) {}

    /**
     * @param  array<string,mixed>  $metadata
     */
    public function metadata(array $metadata): ScopedNarrative
    {
        $this->narrative->metadata($metadata);

        return $this;
    }
}

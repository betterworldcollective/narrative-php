<?php

namespace BetterWorld\Scribe\Contracts;

use BetterWorld\Scribe\Scope;

/**
 * Scopes are Global filters. It can apply to any/all narratives.
 */
interface Scopes
{
    /**
     * Sets the scopes on the narrative.
     *
     * @param  Scope[]  $scopes
     */
    public function withScopes(array $scopes): static;

    /**
     * Gets the scopes on the narrative.
     *
     * @return Scope[]
     */
    public function scopes(): array;
}

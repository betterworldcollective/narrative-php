<?php

namespace BetterWorld\Scribe\Concerns;

use BetterWorld\Scribe\Contracts\Scopes;
use BetterWorld\Scribe\Scope;

/**
 * @phpstan-require-implements Scopes
 */
trait Scopable
{
    /**
     * @var Scope[]
     */
    protected array $__SCOPES__ = [];

    /**
     * @param  Scope[]  $scopes
     */
    public function withScopes(array $scopes): static
    {
        $this->__SCOPES__ = $scopes;

        return $this;
    }

    /**
     * @return Scope[]
     */
    public function scopes(): array
    {
        return $this->__SCOPES__;
    }
}

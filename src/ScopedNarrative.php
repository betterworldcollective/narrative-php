<?php

namespace Narrative;

use Narrative\Contracts\Narrative;

class ScopedNarrative
{
    /**
     * NOTE: Narrative will only use array<string,string> type of scopes.
     *
     * @param  array<string,string|array<mixed>>  $scopes
     */
    public function __construct(public array $scopes, public Narrative $narrative) {}
}

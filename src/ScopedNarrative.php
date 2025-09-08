<?php

namespace Narrative;

use Narrative\Contracts\Narrative;

class ScopedNarrative
{
    /**
     * @param  array<string,string>  $scopes
     */
    public function __construct(public array $scopes, public Narrative $narrative) {}
}

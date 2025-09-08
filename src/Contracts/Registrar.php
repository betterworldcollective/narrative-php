<?php

namespace Narrative\Contracts;

interface Registrar
{
    /**
     * @param  class-string<Narrative>[]  $events
     */
    public function registerEvents(array $events): void;

    /**
     * @param  class-string<Scope>[]  $scopes
     */
    public function registerScopes(array $scopes): void;
}

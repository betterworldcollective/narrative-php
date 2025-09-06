<?php

namespace Narrative\Contracts;

interface Publisher
{
    /** @param Narrative[] $narratives */
    public function publish(array $narratives): void;
}

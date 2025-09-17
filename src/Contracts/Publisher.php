<?php

namespace Narrative\Contracts;

use Narrative\NarrativeService;

interface Publisher
{
    /**
     * @param  array<string,mixed>  $options
     */
    public function __construct(NarrativeService $narrativeService, array $options = []);

    public function publish(Book $book): bool;
}

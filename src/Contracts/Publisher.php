<?php

namespace Narrative\Contracts;

use Narrative\NarrativeService;

interface Publisher
{
    public function __construct(NarrativeService $narrativeService);

    public function publish(Book $book): bool;
}

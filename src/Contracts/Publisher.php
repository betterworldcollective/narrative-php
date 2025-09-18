<?php

namespace Narrative\Contracts;

use Narrative\NarrativeService;

interface Publisher
{
    /**
     * @param  array<string,mixed>  $options
     */
    public function __construct(string $name, NarrativeService $narrativeService, array $options = []);

    public function name(): string;

    public function publish(Book $book): bool;
}

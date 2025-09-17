<?php

namespace Narrative\Publishers;

use Narrative\Contracts\Book;
use Narrative\Contracts\Publisher;
use Narrative\NarrativeService;

class MixpanelPublisher implements Publisher
{
    /**
     * @param  array<string,mixed>  $options
     */
    public function __construct(
        protected NarrativeService $narrativeService,
        protected array $options = []
    ) {}

    public function publish(Book $book): bool
    {
        // TODO: Implement publish() method.

        return false;
    }
}

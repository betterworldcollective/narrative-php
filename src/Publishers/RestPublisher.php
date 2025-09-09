<?php

namespace Narrative\Publishers;

use Narrative\Contracts\Book;
use Narrative\Contracts\Publisher;
use Narrative\NarrativeService;
use Narrative\ScopedNarrative;

class RestPublisher implements Publisher
{
    public function __construct(
        protected NarrativeService $narrativeService
    ) {}

    public function publish(Book $book): bool
    {
        foreach ($book->storylines() as $storyline) {
            $occurrences = [];

            foreach ($book->read($storyline) as $narrative) {
                $scopes = null;

                if ($narrative instanceof ScopedNarrative) {
                    $scopes = $narrative->scopes;
                    $narrative = $narrative->narrative;
                }

                $occurrence = [
                    'event' => $narrative::slug(),
                    'details' => $narrative->values(),
                    'framing' => $narrative->framing(),
                    'occurred_at' => $narrative->occurredAt(),
                ];

                if ($scopes !== null) {
                    $occurrence['scopes'] = $scopes;
                }

                $occurrences[] = $occurrence;
            }

            // TODO: Track failed publishing
            $this->narrativeService->getStorylineConnector($storyline)
                ->listen($occurrences);
        }

        return true;
    }
}

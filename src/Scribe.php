<?php

namespace Narrative;

use Narrative\Contracts\Publisher;

final class Scribe implements Publisher
{
    public function __construct(
        protected NarrativeService $narrativeService
    ) {}

    public function publish(Contracts\Book $book): void
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
                    'event' => $narrative::name(),
                    'details' => $narrative->values(),
                    'framing' => $narrative->framing(),
                    'occurred_at' => $narrative->occurredAt(),
                ];

                if ($scopes !== null) {
                    $occurrence['scopes'] = $scopes;
                }

                $occurrences[] = $occurrence;
            }

            $this->narrativeService->getStorylineConnector($storyline)
                ->listen($occurrences);
        }
    }
}

<?php

namespace Narrative;

use Narrative\Contracts\Publisher;

final class Scribe implements Publisher
{
    public function __construct(
        protected NarrativeService $narrativeService
    ) {}

    public function publish(array $narratives): void
    {
        $storylines = [];

        foreach ($narratives as $narrative) {
            foreach ($narrative::storylines() as $storyline) {
                $storylines[$storyline][] = [
                    'event' => $narrative::event(),
                    // 'scope' => TBD,
                    'details' => $narrative->values(),
                    'framing' => $narrative->framing(),
                    'occurred_at' => $narrative->occurredAt(),
                ];
            }
        }

        foreach ($storylines as $storyline => $occurrences) {
            $this->narrativeService->getStorylineConnector($storyline)
                ->listen($occurrences);
        }

    }
}

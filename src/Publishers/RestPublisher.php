<?php

namespace Narrative\Publishers;

use Narrative\Contracts\Book;
use Narrative\Contracts\Publisher;
use Narrative\Http\Storyline;
use Narrative\NarrativeService;
use Narrative\ScopedNarrative;

use function Narrative\Support\array_value;

class RestPublisher implements Publisher
{
    protected Storyline $storyline;

    /**
     * @param  array<string,mixed>  $options
     */
    public function __construct(
        protected NarrativeService $narrativeService,
        protected array $options = [],
    ) {
        /** @var string $host */
        $host = array_value($this->options, 'host');

        /** @var string $storylineId */
        $storylineId = array_value($this->options, 'storyline_id');

        /** @var string $storylineToken */
        $storylineToken = array_value($this->options, 'storyline_token');

        $this->storyline = new Storyline("{$host}/storylines/{$storylineId}", $storylineToken);

    }

    public function publish(Book $book): bool
    {
        $occurrences = [];

        foreach ($book->read() as $narrative) {
            $scopes = null;

            if ($narrative instanceof ScopedNarrative) {
                $scopes = $narrative->scopes;
                $narrative = $narrative->narrative;
            }

            $occurrence = [
                'event' => $narrative::key(),
                'details' => $narrative->values(),
                'framing' => $narrative->framing(),
                'occurred_at' => $narrative->occurredAt(),
            ];

            if ($scopes !== null) {
                $occurrence['scopes'] = $scopes;
            }

            $occurrences[] = $occurrence;
        }

        return $this->storyline->write($occurrences);
    }
}

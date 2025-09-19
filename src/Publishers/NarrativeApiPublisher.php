<?php

namespace BetterWorld\Scribe\Publishers;

use BetterWorld\Scribe\Contracts\Book;
use BetterWorld\Scribe\Contracts\Publisher;
use BetterWorld\Scribe\Http\Storyline;
use BetterWorld\Scribe\NarrativeService;
use BetterWorld\Scribe\ScopedNarrative;

use function BetterWorld\Scribe\Support\array_value;

class NarrativeApiPublisher implements Publisher
{
    protected Storyline $storyline;

    /**
     * @param  array<string,mixed>  $options
     */
    public function __construct(
        public string $name,
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

    public function name(): string
    {
        return $this->name;
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

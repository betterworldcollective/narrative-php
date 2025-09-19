<?php

namespace BetterWorld\Scribe\Publishers;

use BetterWorld\Scribe\Contracts\Book;
use BetterWorld\Scribe\Contracts\Publisher;
use BetterWorld\Scribe\Contracts\Scopes;
use BetterWorld\Scribe\Http\Storyline;
use BetterWorld\Scribe\NarrativeService;

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
            $occurrence = [
                'event' => $narrative::key(),
                'details' => $narrative->values(),
                'framing' => $narrative->framing(),
                'occurred_at' => $narrative->occurredAt(),
            ];

            $narrativeScopes = $narrative instanceof Scopes ? $narrative->scopes() : [];

            if ($narrativeScopes !== []) {
                $scopes = [];
                $scopeValues = [];

                foreach ($narrativeScopes as $narrativeScope) {
                    $scopes[$narrativeScope->key()] = $narrativeScope->id;

                    $scopeValues[] = [
                        'id' => (string) $narrativeScope->id,
                        'name' => $narrativeScope->name,
                    ];
                }

                $this->storyline->scopes()->values()->upsert($narrativeScope::key(), $scopeValues);

                $occurrence['scopes'] = $scopes;
            }

            $occurrences[] = $occurrence;
        }

        return $this->storyline->write($occurrences);
    }
}

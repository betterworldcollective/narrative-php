<?php

namespace Narrative;

use Narrative\Contracts\Narrative;
use Narrative\Contracts\Publisher;

final class Scribe
{
    public function __construct(
        protected Contracts\Book $book,
        protected Publisher $publisher,
        protected bool $autoPublish = true
    ) {}

    /**
     * @param  array{
     *     host:string|null,
     *     default_storyline:string|null,
     *     storylines: array<string, array{id:string|null, token:string|null}>|null,
     *     default_publisher: class-string<Publisher>|null,
     *     publishers: array<string, class-string<Publisher>>,
     *     auto_publish: bool
     * }  $config
     */
    public static function make(array $config): static
    {
        $narrativeService = new NarrativeService($config);

        return new self(
            new Book,
            $narrativeService->getPublisher(),
            $narrativeService->shouldAutoPublish()
        );
    }

    public function write(Narrative|ScopedNarrative $narrative): static
    {
        $this->book->write($narrative);

        return $this;
    }

    public function publish(): static
    {
        $this->book->publish($this->publisher);

        return $this;
    }

    public function __destruct()
    {
        if ($this->autoPublish) {
            $this->book->publish($this->publisher);
        }
    }
}

<?php

namespace Narrative;

use Narrative\Contracts\Narrative;
use Narrative\Contracts\Publisher;

final class Scribe
{
    /**
     * @param  Publisher|Publisher[]  $publisher
     */
    public function __construct(
        protected Contracts\Book $book,
        protected Publisher|array $publisher,
        protected bool $autoPublish = true
    ) {}

    /**
     * @param  array{
     *     host:string,
     *     default_storyline:string,
     *     storylines: array<string, array{id:string, token:string}>,
     *     default_publisher: string|string[],
     *     publishers: array<string, array{class:class-string<Publisher>, option:array<string,mixed>}>,
     *     auto_publish: bool
     * }  $config
     */
    public static function make(array $config): static
    {
        $narrativeService = new NarrativeService($config);

        $defaultPublisher = $narrativeService->getDefaultPublisher();

        $publisher = is_array($defaultPublisher)
            ? $narrativeService->getPublishers($defaultPublisher)
            : $narrativeService->getPublisher($defaultPublisher);

        return new self(
            new Book,
            $publisher,
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

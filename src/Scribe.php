<?php

namespace Narrative;

use Narrative\Contracts\Narrative;
use Narrative\Contracts\Publisher;

final class Scribe
{
    protected NarrativeService $narrativeService;

    /**
     * @param  array{
     *     publishers: array<string,array{class:class-string<Publisher>,options:array<string,mixed>}>,
     *     default_book: string,
     *     books: array<string, array{publishers: string[]}>,
     *     auto_publish: bool
     * }  $config
     */
    public function __construct(
        array $config
    ) {
        $this->narrativeService = new NarrativeService($config);
    }

    /**
     * @param  array{
     *     publishers: array<string,array{class:class-string<Publisher>,options:array<string,mixed>}>,
     *     default_book: string,
     *     books: array<string, array{publishers: string[]}>,
     *     auto_publish: bool
     * }  $config
     */
    public static function make(array $config): static
    {
        return new self($config);
    }

    public function write(Narrative|ScopedNarrative $narrative): static
    {
        $baseNarrative = $narrative instanceof ScopedNarrative ? $narrative->narrative : $narrative;

        foreach ($baseNarrative::books() as $book) {
            $this->narrativeService->getBook($book)->write($narrative);
        }

        return $this;
    }

    public function publish(): void
    {
        foreach ($this->narrativeService->getBooks() as $book) {
            $book->publish();
        }
    }

    public function __destruct()
    {
        if ($this->narrativeService->shouldAutoPublish()) {
            $this->publish();
        }
    }
}

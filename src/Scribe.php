<?php

namespace Narrative;

use Narrative\Contracts\Narrative;
use Narrative\Contracts\Publisher;

final class Scribe
{
    protected array $publishers = [];

    public function __construct(
        protected Contracts\Book $book,
        Publisher|array $publishers,
        protected bool $autoPublish = true
    ) {
        $this->publishers = is_array($publishers) ? $publishers : [$publishers];
    }

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
        
        $publishers = [];
        $publisherConfigs = $config['publishers'] ?? [];
        
        foreach ($publisherConfigs as $publisherClass) {
            $publishers[] = new $publisherClass($narrativeService);
        }

        return new self(
            new Book,
            $publishers,
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
        foreach ($this->publishers as $publisher) {
            $this->book->publish($publisher);
        }

        return $this;
    }

    public function __destruct()
    {
        if ($this->autoPublish) {
            foreach ($this->publishers as $publisher) {
                $this->book->publish($publisher);
            }
        }
    }

    public function getPublishers(): array
    {
        return $this->publishers;
    }
}

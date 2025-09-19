<?php

namespace BetterWorld\Scribe;

use BetterWorld\Scribe\Contracts\Narrative;
use BetterWorld\Scribe\Contracts\Publisher;

class Book implements Contracts\Book
{
    /**
     * @var array<Narrative|ScopedNarrative>
     */
    protected array $narratives = [];

    /** @var string[] */
    protected array $publishedBy = [];

    /** @param Publisher[] $publishers  */
    public function __construct(
        public string $name,
        protected array $publishers,
    ) {}

    public function name(): string
    {
        return $this->name;
    }

    public function write(Narrative|ScopedNarrative $narrative): static
    {
        $this->narratives[] = $narrative;

        return $this;
    }

    /** @return array<Narrative|ScopedNarrative> */
    public function read(): array
    {
        return $this->narratives;
    }

    public function publishers(): array
    {
        return $this->publishers;
    }

    public function publish(): void
    {
        foreach ($this->publishers() as $publisher) {
            if (in_array($publisher->name(), $this->publishedBy)) {
                continue;
            }

            if ($publisher->publish($this)) {
                $this->publishedBy[] = $publisher->name();
            }
        }
    }
}

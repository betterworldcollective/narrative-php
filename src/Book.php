<?php

namespace Narrative;

use Narrative\Contracts\Narrative;
use Narrative\Contracts\Publisher;

class Book implements Contracts\Book
{
    /**
     * @var array<Narrative|ScopedNarrative>
     */
    protected array $narratives = [];

    /** @var array<class-string<Publisher>> */
    protected array $publishedBy = [];

    /** @param Publisher[] $publishers  */
    public function __construct(
        protected array $publishers,
    ) {}

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
            if (in_array($publisher::class, $this->publishedBy)) {
                continue;
            }

            if ($publisher->publish($this)) {
                $this->publishedBy[] = $publisher::class;
            }
        }
    }
}

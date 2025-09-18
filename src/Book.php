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

    /** @var array<string|null> */
    protected array $storylines = [];

    protected bool $isPublished = false;

    public function write(Narrative|ScopedNarrative $narrative): static
    {
        $this->narratives[] = $narrative;

        $baseNarrative = $narrative instanceof ScopedNarrative ? $narrative->narrative : $narrative;

        $this->storylines = array_unique(
            array_merge($this->storylines, $baseNarrative::storylines())
        );

        return $this;
    }

    /**
     * @param  string|false|null  $storyline  false = read all, null = use default storyline, string = use this storyline
     * @return array<Narrative|ScopedNarrative>
     */
    public function read(string|false|null $storyline = false): array
    {
        if ($storyline === false) {
            return $this->narratives;
        }

        $narratives = [];

        foreach ($this->narratives as $narrative) {
            $baseNarrative = $narrative instanceof ScopedNarrative ? $narrative->narrative : $narrative;

            if (in_array($storyline, $baseNarrative::storylines())) {
                $narratives[] = $narrative;
            }
        }

        return $narratives;
    }

    /**
     * @return array<string|null>
     */
    public function storylines(): array
    {
        return $this->storylines;
    }

    /**
     * @param  Publisher|Publisher[]  $publisher
     */
    public function publish(Publisher|array $publisher): void
    {
        if ($this->isPublished) {
            return;
        }

        $publishers = is_array($publisher) ? $publisher : [$publisher];

        foreach ($publishers as $publisher) {
            $publisher->publish($this);
        }

        $this->isPublished = true;
    }

    public function isPublished(): bool
    {
        return $this->isPublished;
    }
}

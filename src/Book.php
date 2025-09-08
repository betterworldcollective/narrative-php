<?php

namespace Narrative;

use Narrative\Contracts\Narrative;
use Narrative\Contracts\Publisher;

class Book implements Contracts\Book
{
    /**
     * @var array<string, array<Narrative|ScopedNarrative>>
     */
    protected array $narratives = [];

    protected bool $isPublished = false;

    public function write(Narrative|ScopedNarrative $narrative): static
    {
        $mainNarrative = $narrative;

        $narrative = $narrative instanceof ScopedNarrative ? $narrative->narrative : $narrative;

        foreach ($narrative::storylines() as $storyline) {
            $this->narratives[$storyline][] = $mainNarrative;
        }

        return $this;
    }

    /** @return array<Narrative|ScopedNarrative> */
    public function read(?string $storyline = null): array
    {
        if ($storyline === null) {
            $allNarratives = [];

            foreach ($this->narratives as $narratives) {
                $allNarratives = array_merge($allNarratives, $narratives);
            }

            return $allNarratives;
        }

        return $this->narratives[$storyline];
    }

    /**
     * @return string[]
     */
    public function storylines(): array
    {
        return array_keys($this->narratives);
    }

    public function publish(Publisher $publisher): void
    {
        if ($this->isPublished) {
            return;
        }

        $this->isPublished = $publisher->publish($this);
    }

    public function isPublished(): bool
    {
        return $this->isPublished;
    }
}

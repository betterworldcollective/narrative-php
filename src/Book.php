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

    protected array $publishedPublishers = [];

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
        $publisherClass = get_class($publisher);
        
        if (isset($this->publishedPublishers[$publisherClass])) {
            return;
        }

        $this->publishedPublishers[$publisherClass] = $publisher->publish($this);
    }

    public function isPublished(): bool
    {
        return !empty($this->publishedPublishers);
    }

    public function isPublishedBy(Publisher $publisher): bool
    {
        $publisherClass = get_class($publisher);
        return isset($this->publishedPublishers[$publisherClass]);
    }
}

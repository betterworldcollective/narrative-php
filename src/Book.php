<?php

namespace Narrative;

use Narrative\Contracts\Narrative;

class Book implements Contracts\Book
{
    /**
     * @var array<string, array<Narrative|ScopedNarrative>>
     */
    protected array $narratives = [];

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
        $storylines = [];

        foreach ($this->narratives as $storyline => $narratives) {
            $storylines[] = $storyline;
        }

        return $storylines;
    }
}

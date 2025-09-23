<?php

/**
 * THIS IS JUST AN EXAMPLE FILE. THIS IS NOT INTENDED TO BE USED IN YOUR APPLICATION.
 */

namespace BetterWorld\Scribe\Examples;

use BetterWorld\Scribe\Attributes\Books;
use BetterWorld\Scribe\Attributes\Context;
use BetterWorld\Scribe\Attributes\Key;
use BetterWorld\Scribe\Attributes\OccurredAt;
use BetterWorld\Scribe\Narrative;
use BetterWorld\Scribe\Support\ArrayList;
use BetterWorld\Scribe\Support\Date;
use BetterWorld\Scribe\Support\DateTime;
use BetterWorld\Scribe\Support\Json;
use BetterWorld\Scribe\Support\Time;

#[Books('main')]
#[Context('This is an example.')]
class NarratorUsedNarrative extends Narrative
{
    public function __construct(
        #[Context('This is a string field.')]
        public string $name,

        #[Context('This is an integer field.')]
        public int $count,

        #[Context('This is a float field.')]
        public float $amount,

        #[Context('This is a boolean field.')]
        public bool $isActive,

        #[Context('This is a list field.')]
        public ArrayList $tags,

        #[Context('This is a time field.')]
        public Time $time,

        #[Context('This is a date field.')]
        public Date $date,

        #[Context('This is a json field.')]
        public ?Json $json,

        #[Context('This is a datetime field.')]
        #[Key('datetime_created')]
        #[OccurredAt]
        public ?DateTime $createdAt = null,
    ) {}

    public function framing(): ?string
    {
        return "This is a custom framing. By: {$this->name}";
    }
}

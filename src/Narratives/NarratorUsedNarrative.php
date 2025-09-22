<?php

namespace BetterWorld\Scribe\Narratives;

use BetterWorld\Scribe\Attributes\Books;
use BetterWorld\Scribe\Attributes\Context;
use BetterWorld\Scribe\Attributes\Key;
use BetterWorld\Scribe\Attributes\Name;
use BetterWorld\Scribe\Support\ArrayList;
use BetterWorld\Scribe\Support\Date;
use BetterWorld\Scribe\Support\Json;
use BetterWorld\Scribe\Support\Time;
use DateTime;

#[Books('main')]
#[Context('This is an example.')]
#[Key('example:narrative:used')]
#[Name('Narrator Used Example')]
class NarratorUsedNarrative extends Narrative
{
    public function __construct(
        #[Context('This is an example string property.')]
        #[Key('example_message')]
        public ?string $message,

        #[Context('This is an example int property.')]
        public int $intExample,

        #[Context('This is an example float property.')]
        public float $floatExample,

        #[Context('This is an example bool property.')]
        public bool $boolExample,

        #[Context('This is an example list property.')]
        public ArrayList $listExample,

        #[Context('This is an example DateTime property.')]
        public DateTime $dateTimeExample,

        #[Context('This is an example Date property.')]
        public Date $dateExample,

        #[Context('This is an example Time property.')]
        public Time $timeExample,

        #[Context('This is an example JSON property.')]
        public Json $jsonExample,
    ) {}

    public function framing(): ?string
    {
        return "This is a modified message that allows for interpolation. Check out the original message below: \n {$this->message}";
    }
}

<?php

namespace BetterWorld\Scribe\Narratives;

use BetterWorld\Scribe\Attributes\Books;
use BetterWorld\Scribe\Attributes\Context;
use BetterWorld\Scribe\Attributes\Key;
use BetterWorld\Scribe\Attributes\Name;
use BetterWorld\Scribe\Attributes\OccurredAt;
use BetterWorld\Scribe\Concerns\Narrator;
use BetterWorld\Scribe\Contracts\Narrative;
use BetterWorld\Scribe\Enums\DataType;

#[Books('main')]
#[Context('This is an example.')]
#[Key('example:narrative:used')]
#[Name('Narrator Used Example')]
class NarratorUsedNarrative implements Narrative
{
    use Narrator;

    public function __construct(
        #[Context('This is the example message.')]
        #[Key('example_message')]
        public string $message,

        #[OccurredAt]
        #[Context('This is when the example was used.', DataType::Datetime)]
        public string $usedAt,
    ) {}

    public function framing(): ?string
    {
        return "This is a modified message that allows for interpolation. Check out the original message below: \n {$this->message}";
    }
}

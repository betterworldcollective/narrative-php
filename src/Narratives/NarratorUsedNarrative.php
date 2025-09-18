<?php

namespace Narrative\Narratives;

use Narrative\Attributes\Books;
use Narrative\Attributes\Context;
use Narrative\Attributes\Key;
use Narrative\Attributes\Name;
use Narrative\Attributes\OccurredAt;
use Narrative\Concerns\Narrator;
use Narrative\Contracts\Narrative;
use Narrative\Enums\DataType;

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

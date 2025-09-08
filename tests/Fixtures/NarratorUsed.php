<?php

namespace Tests\Fixtures;

use Narrative\Attributes\Context;
use Narrative\Attributes\Name;
use Narrative\Attributes\Slug;
use Narrative\Attributes\Storylines;
use Narrative\Concerns\Narrator;
use Narrative\Contracts\Narrative;

#[Storylines('default', 'example')]
#[Slug('narrator-example-used')]
#[Context('This is an example of how to use the Narrative contract.')]
#[Name('[Example] Narrator Used')]
final class NarratorUsed implements Narrative
{
    use Narrator;

    public function __construct(
        #[Slug('narrator-message')]
        #[Context('A sample message used by the Narrator.')]
        public string $message
    ) {}

    public function framing(): ?string
    {
        return "[Narrator Message] {$this->message}";
    }
}

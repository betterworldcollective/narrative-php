<?php

namespace Narrative;

use Narrative\Attributes\Books;
use Narrative\Attributes\Context;
use Narrative\Attributes\Key;
use Narrative\Attributes\Name;
use Narrative\Concerns\Narrator;
use Narrative\Contracts\Narrative;

#[Books('default', 'example')]
#[Key('narrator-example-used')]
#[Context('This is an example of how to use the Narrative contract.')]
#[Name('[Example] Narrator Used')]
final class NarratorUsed implements Narrative
{
    use Narrator;

    public function __construct(
        #[Key('narrator-message')]
        #[Context('A sample message used by the Narrator.')]
        public string $message
    ) {}

    //    public function framing(): ?string
    //    {
    //        return "[Narrator Message] {$this->message}";
    //    }
}

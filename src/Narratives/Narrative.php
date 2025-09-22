<?php

namespace BetterWorld\Scribe\Narratives;

use BetterWorld\Scribe\Concerns\Metadatable;
use BetterWorld\Scribe\Concerns\Narrator;
use BetterWorld\Scribe\Concerns\Scopable;
use BetterWorld\Scribe\Contracts\Metadata;
use BetterWorld\Scribe\Contracts\Narrative as NarrativeContract;
use BetterWorld\Scribe\Contracts\Scopes;

class Narrative implements Metadata, NarrativeContract, Scopes
{
    use Metadatable, Narrator, Scopable;
}

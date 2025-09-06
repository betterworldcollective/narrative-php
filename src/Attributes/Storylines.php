<?php

namespace Narrative\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Storylines
{
    /** @var string[] */
    public array $storylines = [];

    public function __construct(string ...$storylines)
    {
        $this->storylines = $storylines;
    }
}

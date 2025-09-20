<?php

namespace BetterWorld\Scribe\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class Context
{
    public function __construct(public string $context) {}
}

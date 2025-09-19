<?php

namespace BetterWorld\Scribe\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class Key
{
    public function __construct(public string $key) {}
}

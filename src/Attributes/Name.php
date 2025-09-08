<?php

namespace Narrative\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Name
{
    public function __construct(public string $name) {}
}

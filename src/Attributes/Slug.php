<?php

namespace Narrative\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class Slug
{
    public function __construct(public string $slug) {}
}

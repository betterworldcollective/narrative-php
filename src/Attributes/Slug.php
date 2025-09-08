<?php

namespace Narrative\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Slug
{
    public function __construct(public string $slug) {}
}

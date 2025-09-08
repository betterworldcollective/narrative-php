<?php

namespace Narrative\Attributes;

use Attribute;

use function Narrative\Support\delimited_case;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class Slug
{
    public function __construct(protected string $slug) {}

    public function getSlug(): string
    {
        return delimited_case($this->slug, '-');
    }
}

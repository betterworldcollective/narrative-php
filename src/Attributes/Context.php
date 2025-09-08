<?php

namespace Narrative\Attributes;

use Attribute;
use Narrative\Enums\DataType;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY)]
class Context
{
    public function __construct(public string $context, public DataType $type = DataType::String) {}
}

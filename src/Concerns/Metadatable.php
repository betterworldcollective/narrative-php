<?php

namespace BetterWorld\Scribe\Concerns;

trait Metadatable
{
    /**
     * @var array<string,mixed>
     */
    protected array $__METADATA__ = [];

    /**
     * @param  array<string,mixed>  $metadata
     */
    public function withMetadata(array $metadata): static
    {
        $this->__METADATA__ = $metadata;

        return $this;
    }

    /**
     * @return array<string,mixed>
     */
    public function metadata(): array
    {
        return $this->__METADATA__;
    }
}

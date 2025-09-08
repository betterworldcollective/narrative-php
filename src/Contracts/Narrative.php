<?php

namespace Narrative\Contracts;

use Narrative\ScopedNarrative;

interface Narrative
{
    /**
     * The storylines that the narrative belongs to.
     *
     * @return string[]
     */
    public static function storylines(): array;

    /**
     * The slug of the event that occurred.
     */
    public static function slug(): ?string;

    /**
     * The name of the event that occurred.
     */
    public static function name(): string;

    /**
     * A description of the event to provide more context.
     */
    public static function context(): string;

    /**
     * The schema definition of the payload of the event.
     *
     * @return array<string,mixed>
     */
    public static function definitions(): array;

    /**
     * A key-value pair array of the event's payload.
     *
     * @return array<string,mixed>
     */
    public function values(): array;

    /**
     * A customized interpolated string to frame the narrative.
     */
    public function framing(): ?string;

    /**
     * The datetime of when the event occurred.
     */
    public function occurredAt(): string;

    /**
     * Add a scope to this narrative.
     *
     * @param  array<string,string>  $scopes
     */
    public function scopedBy(array $scopes): ScopedNarrative;
}

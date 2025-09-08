<?php

namespace Narrative\Concerns;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Narrative\Attributes\Context;
use Narrative\Attributes\Name;
use Narrative\Attributes\OccurredAt;
use Narrative\Attributes\Slug;
use Narrative\Attributes\Storylines;
use Narrative\Contracts\Narrative;
use Narrative\Exceptions\MissingContextException;
use Narrative\ScopedNarrative;
use ReflectionClass;
use ReflectionProperty;

/**
 * @phpstan-require-implements Narrative
 */
trait Narrator
{
    public static function slug(): ?string
    {
        $event = (new ReflectionClass(static::class))
            ->getAttributes(Slug::class);

        return empty($event) ? null : $event[0]->newInstance()->slug;
    }

    public static function name(): string
    {
        $event = (new ReflectionClass(static::class))
            ->getAttributes(Name::class);

        return empty($event)
            ? Str::of(static::class)->classBasename()->beforeLast('Narrative')->headline()->toString()
            : $event[0]->newInstance()->name;
    }

    public static function context(): string
    {
        $context = (new ReflectionClass(static::class))
            ->getAttributes(Context::class);

        if (empty($context)) {
            throw new MissingContextException;
        }

        return $context[0]->newInstance()->context;
    }

    /** @return array<string,mixed> */
    public static function definitions(): array
    {
        /** @var array<string, mixed> $definitions */
        $definitions = collect((new ReflectionClass(static::class))->getProperties(ReflectionProperty::IS_PUBLIC))
            ->filter(fn (ReflectionProperty $property) => (string) $property->getType() === 'string')
            ->flatMap(function (ReflectionProperty $property) {
                $context = $property->getAttributes(Context::class);

                if (empty($context)) {
                    throw new MissingContextException('Context attribute is required.');
                }

                return [
                    Str::snake($property->getName()) => [
                        'type' => $context[0]->newInstance()->type->value,
                        'context' => $context[0]->newInstance()->context,
                    ],
                ];
            })
            ->toArray();

        return $definitions;
    }

    /** @return array<string, mixed> */
    public function values(): array
    {
        /** @var array<string,mixed> $values */
        $values = collect((new ReflectionClass(static::class))->getProperties(ReflectionProperty::IS_PUBLIC))
            ->filter(fn (ReflectionProperty $property) => (string) $property->getType() === 'string')
            ->flatMap(fn (ReflectionProperty $property) => [Str::snake($property->getName()) => $property->getValue($this)])
            ->toArray();

        return $values;
    }

    public function framing(): ?string
    {
        return null;
    }

    public function occurredAt(): string
    {
        foreach ((new ReflectionClass(static::class))->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $attributes = $property->getAttributes(OccurredAt::class);

            if (! empty($attributes)) {
                $occurredAt = $property->getValue($this);

                return is_string($occurredAt) ? $occurredAt : throw new InvalidArgumentException('Value must be a string.');
            }
        }

        return Carbon::now()->toDateTimeString();
    }

    /** @return string[]  */
    public static function storylines(): array
    {
        $storylines = (new ReflectionClass(static::class))
            ->getAttributes(Storylines::class);

        return empty($storylines) ? ['default'] : $storylines[0]->newInstance()->storylines;

    }

    /**
     * @param  array<string,string>  $scopes
     */
    public function scopedBy(array $scopes): ScopedNarrative
    {
        return new ScopedNarrative($scopes, $this);
    }
}

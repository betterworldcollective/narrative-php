<?php

namespace Narrative\Concerns;

use DateTime;
use DateTimeZone;
use Narrative\Attributes\Context;
use Narrative\Attributes\Name;
use Narrative\Attributes\OccurredAt;
use Narrative\Attributes\Slug;
use Narrative\Attributes\Storylines;
use Narrative\Contracts\Narrative;
use Narrative\Exceptions\InvalidDatetimeStringException;
use Narrative\Exceptions\MissingContextException;
use Narrative\ScopedNarrative;
use PrinceJohn\Reflect\Reflect;
use ReflectionClass;
use ReflectionProperty;

use function Narrative\Support\between;
use function Narrative\Support\delimited_case;
use function Narrative\Support\headline;
use function Narrative\Support\isValidDateTime;

/**
 * @phpstan-require-implements Narrative
 */
trait Narrator
{
    /** @return array<string|null>  */
    public static function storylines(): array
    {
        $storylines = Reflect::class(static::class)->getAttributeInstance(Storylines::class);

        if ($storylines === null) {
            return [null];
        }

        return $storylines->storylines;
    }

    public static function slug(): ?string
    {
        $slug = Reflect::class(static::class)->getAttributeInstance(Slug::class)?->getSlug();

        return $slug ?? delimited_case(between(static::class, '\\', 'Narrative'), '-', '/[^a-z0-9:]+/');
    }

    public static function name(): string
    {
        $name = Reflect::class(static::class)->getAttributeInstance(Name::class);

        if ($name === null) {
            return headline(between(static::class, '\\', 'Narrative'));
        }

        return $name->name;
    }

    public static function context(): string
    {
        return Reflect::class(static::class)->getOrFailAttributeInstance(Context::class)->context;
    }

    /** @return array<string,mixed> */
    public static function definitions(): array
    {
        $definitions = [];

        foreach ((new ReflectionClass(static::class))->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            if ((string) $property->getType() !== 'string') {
                continue;
            }

            $context = $property->getAttributes(Context::class);

            if (empty($context)) {
                throw new MissingContextException('[Context] attribute is missing.');
            }

            $slug = ($property->getAttributes(Slug::class)[0] ?? null)?->newInstance()->getSlug()
                ?? delimited_case($property->getName());

            $definitions[$slug] = [
                'type' => $context[0]->newInstance()->type->value,
                'context' => $context[0]->newInstance()->context,
            ];
        }

        return $definitions;
    }

    /** @return array<string, mixed> */
    public function values(): array
    {
        $values = [];

        foreach ((new ReflectionClass(static::class))->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            if ((string) $property->getType() !== 'string') {
                continue;
            }

            $slug = ($property->getAttributes(Slug::class)[0] ?? null)?->newInstance()->getSlug()
                ?? delimited_case($property->getName());

            $values[$slug] = $property->getValue($this);
        }

        return $values;
    }

    public function framing(): ?string
    {
        return null;
    }

    public function occurredAt(): string
    {
        $format = 'Y-m-d H:i:s';

        foreach ((new ReflectionClass(static::class))->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            if ((string) $property->getType() !== 'string') {
                continue;
            }

            $attributes = $property->getAttributes(OccurredAt::class);

            if (! empty($attributes)) {
                /** @var string $occurredAt */
                $occurredAt = $property->getValue($this);

                return isValidDateTime($occurredAt, $format)
                    ? $occurredAt
                    : throw new InvalidDatetimeStringException("[{$occurredAt}] is not a {$format} datetime string.");
            }
        }

        return (new DateTime(timezone: new DateTimeZone('UTC')))->format($format);
    }

    /**
     * @param  array<string,string>  $scopes
     */
    public function scopedBy(array $scopes): ScopedNarrative
    {
        return new ScopedNarrative($scopes, $this);
    }
}

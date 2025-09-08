<?php

namespace Narrative\Concerns;

use DateTime;
use DateTimeZone;
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

use function Narrative\Support\between;
use function Narrative\Support\delimited_case;
use function Narrative\Support\headline;

/**
 * @phpstan-require-implements Narrative
 */
trait Narrator
{
    /** @return string[]  */
    public static function storylines(): array
    {
        $storylines = (new ReflectionClass(static::class))
            ->getAttributes(Storylines::class);

        return empty($storylines) ? ['default'] : $storylines[0]->newInstance()->storylines;
    }

    public static function slug(): ?string
    {
        $event = (new ReflectionClass(static::class))
            ->getAttributes(Slug::class);

        return empty($event) ? null : $event[0]->newInstance()->getSlug();
    }

    public static function name(): string
    {
        $event = (new ReflectionClass(static::class))
            ->getAttributes(Name::class);

        return empty($event)
            ? headline(between(static::class, '\\', 'Narrative'))
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
        $definitions = [];

        foreach ((new ReflectionClass(static::class))->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            if ((string) $property->getType() !== 'string') {
                continue;
            }

            $context = $property->getAttributes(Context::class);

            if (empty($context)) {
                throw new MissingContextException('Context attribute is required.');
            }

            $definitions[delimited_case($property->getName())] = [
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

            $values[delimited_case($property->getName())] = $property->getValue($this);
        }

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

                // TODO:: Check for valid date, throw custom exception on failure
                return is_string($occurredAt) ? $occurredAt : throw new InvalidArgumentException('Value must be a string.');
            }
        }

        return (new DateTime(timezone: new DateTimeZone('UTC')))->format('Y-m-d H:i:s');
    }

    /**
     * @param  array<string,string>  $scopes
     */
    public function scopedBy(array $scopes): ScopedNarrative
    {
        return new ScopedNarrative($scopes, $this);
    }
}

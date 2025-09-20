<?php

namespace BetterWorld\Scribe\Concerns;

use BetterWorld\Scribe\Attributes\Books;
use BetterWorld\Scribe\Attributes\Context;
use BetterWorld\Scribe\Attributes\Key;
use BetterWorld\Scribe\Attributes\Name;
use BetterWorld\Scribe\Attributes\OccurredAt;
use BetterWorld\Scribe\Contracts\Narrative;
use BetterWorld\Scribe\Enums\DataType;
use BetterWorld\Scribe\Exceptions\InvalidDatetimeStringException;
use BetterWorld\Scribe\Exceptions\InvalidPropertyTypeException;
use BetterWorld\Scribe\Exceptions\MissingContextException;
use BetterWorld\Scribe\Support\ArrayList;
use BetterWorld\Scribe\Support\Date;
use BetterWorld\Scribe\Support\Json;
use BetterWorld\Scribe\Support\Time;
use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use PrinceJohn\Reflect\Reflect;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;
use RuntimeException;

use function BetterWorld\Scribe\Support\between;
use function BetterWorld\Scribe\Support\delimited_case;
use function BetterWorld\Scribe\Support\headline;
use function BetterWorld\Scribe\Support\isValidDateTime;

/**
 * @phpstan-require-implements Narrative
 */
trait Narrator
{
    /** @return array<string|null>  */
    public static function books(): array
    {
        $books = Reflect::class(static::class)->getAttributeInstance(Books::class);

        if ($books === null) {
            return [null];
        }

        return $books->books;
    }

    public static function key(): ?string
    {
        $key = Reflect::class(static::class)->getAttributeInstance(Key::class)?->key;

        return delimited_case(
            string: $key ?? between(static::class, '\\', 'Narrative'),
            characters: '/[^a-z0-9:]+/'
        );
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
            $propertyName = $property->getName();
            $propertyType = $property->getType();

            if (! $propertyType instanceof ReflectionNamedType) {
                throw InvalidPropertyTypeException::make($propertyName);
            }

            $narrativeType = match ($propertyType->getName()) {
                'string' => DataType::STRING,
                'int' => DataType::INTEGER,
                'float','double' => DataType::FLOAT,
                'bool' => DataType::BOOLEAN,
                ArrayList::class => DataType::LIST,
                DateTime::class, DateTimeImmutable::class => DataType::DATETIME,
                Date::class => DataType::DATE,
                Time::class => DataType::TIME,
                Json::class => DataType::JSON,
                default => throw InvalidPropertyTypeException::make($propertyName)
            };

            /** @var Key|null $key */
            $key = ($property->getAttributes(Key::class)[0] ?? null)?->newInstance();

            $key = is_null($key) ? delimited_case($propertyName) : $key->key;

            /** @var Context|null $context */
            $context = ($property->getAttributes(Context::class)[0] ?? null)?->newInstance();

            if ($context === null) {
                throw MissingContextException::make();
            }

            $definitions[$key] = [
                'type' => $narrativeType->value,
                'nullable' => $propertyType->allowsNull(),
                'context' => $context->context,
            ];
        }

        return $definitions;
    }

    /** @return array<string, mixed> */
    public function values(): array
    {
        $values = [];

        foreach ((new ReflectionClass(static::class))->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $propertyName = $property->getName();
            $propertyType = $property->getType();

            if (! $propertyType instanceof ReflectionNamedType) {
                throw InvalidPropertyTypeException::make($propertyName);
            }

            $value = $property->getValue($this);

            $normalizedValue = match ($propertyType->getName()) {
                'string' => $value,
                'int' => $value,
                'float','double' => $value,
                'bool' => $value,
                ArrayList::class => $value instanceof ArrayList ? $value->getList() : throw new RuntimeException,
                DateTime::class, DateTimeImmutable::class => $value instanceof DateTime || $value instanceof DateTimeImmutable
                        ? $value->format('Y-m-d H:i:s')
                        : throw new RuntimeException,
                Date::class => $value instanceof Date ? $value->toString() : throw new RuntimeException,
                Time::class => $value instanceof Time ? $value->toString() : throw new RuntimeException,
                Json::class => $value instanceof Json ? $value->toString() : throw new RuntimeException,
                default => throw InvalidPropertyTypeException::make($propertyName)
            };

            /** @var Key|null $key */
            $key = ($property->getAttributes(Key::class)[0] ?? null)?->newInstance();

            $key = is_null($key) ? delimited_case($propertyName) : $key->key;

            $values[$key] = $normalizedValue;
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
                    : throw InvalidDatetimeStringException::make($occurredAt, $format);
            }
        }

        return (new DateTime(timezone: new DateTimeZone('UTC')))->format($format);
    }
}

<?php

namespace BetterWorld\Scribe\Support;

use BetterWorld\Scribe\Exceptions\InvalidDatetimeStringException;
use DateTimeImmutable;
use DateTimeInterface;

final readonly class DateTime
{
    private DateTimeInterface $datetime;

    public function __construct(
        DateTimeInterface|string $datetime
    ) {
        if ($datetime instanceof DateTimeInterface) {
            $this->datetime = $datetime;
        } else {
            $dti = DateTimeImmutable::createFromFormat(DATETIME_FORMAT, $datetime);

            if ($dti === false || $dti->format(DATETIME_FORMAT) !== $datetime) {
                throw InvalidDatetimeStringException::make($datetime, DATETIME_FORMAT);
            }

            $this->datetime = $dti;
        }
    }

    public static function is(DateTimeInterface|string $datetime): DateTime
    {
        return new self($datetime);
    }

    public function toString(): string
    {
        return $this->datetime->format(DATETIME_FORMAT);
    }
}

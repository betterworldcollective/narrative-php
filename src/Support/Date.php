<?php

namespace BetterWorld\Scribe\Support;

use BetterWorld\Scribe\Exceptions\InvalidDatetimeStringException;
use DateTimeImmutable;
use DateTimeInterface;

final readonly class Date
{
    private DateTimeInterface $date;

    public function __construct(
        DateTimeInterface|string $date
    ) {
        if ($date instanceof DateTimeInterface) {
            $this->date = $date;
        } else {
            $dti = DateTimeImmutable::createFromFormat(DATE_FORMAT, $date);

            if ($dti === false || $dti->format(DATE_FORMAT) !== $date) {
                throw InvalidDatetimeStringException::make($date, DATE_FORMAT);
            }

            $this->date = $dti;
        }
    }

    public static function of(DateTimeInterface|string $date): Date
    {
        return new self($date);
    }

    public function toString(): string
    {
        return $this->date->format(DATE_FORMAT);
    }
}

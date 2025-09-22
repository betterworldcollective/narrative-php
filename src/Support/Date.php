<?php

namespace BetterWorld\Scribe\Support;

use BetterWorld\Scribe\Exceptions\InvalidDatetimeStringException;
use DateTimeImmutable;
use DateTimeInterface;

final class Date
{
    private DateTimeInterface $date;

    public function __construct(
        DateTimeInterface|string $date
    ) {
        if ($date instanceof DateTimeInterface) {
            $this->date = $date;
        } else {
            $dti = DateTimeImmutable::createFromFormat(DATE_FORMAT, $date);

            if ($dti instanceof DateTimeImmutable && $dti->format(DATE_FORMAT) === $date) {
                $this->date = $dti;
            }

            throw InvalidDatetimeStringException::make($date, DATE_FORMAT);
        }
    }

    public static function is(DateTimeInterface|string $date): Date
    {
        return new self($date);
    }

    public function toString(): string
    {
        return $this->date->format(DATE_FORMAT);
    }
}

<?php

namespace BetterWorld\Scribe\Support;

use BetterWorld\Scribe\Exceptions\InvalidDatetimeStringException;
use DateTime;
use DateTimeImmutable;

final class Date
{
    private DateTime|DateTimeImmutable $date;

    public function __construct(
        DateTime|DateTimeImmutable|string $date
    ) {
        if ($date instanceof DateTime || $date instanceof DateTimeImmutable) {
            $this->date = $date;
        } else {
            $dti = DateTimeImmutable::createFromFormat(DATE_FORMAT, $date);

            if ($dti instanceof DateTimeImmutable && $dti->format(DATE_FORMAT) === $date) {
                $this->date = $dti;
            }

            throw InvalidDatetimeStringException::make($date, DATE_FORMAT);
        }
    }

    public static function is(DateTime|DateTimeImmutable|string $date): Date
    {
        return new self($date);
    }

    public function toString(): string
    {
        return $this->date->format(DATE_FORMAT);
    }
}

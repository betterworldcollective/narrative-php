<?php

namespace BetterWorld\Scribe\Support;

use BetterWorld\Scribe\Exceptions\InvalidDatetimeStringException;
use DateTime;
use DateTimeImmutable;

final class Time
{
    private DateTime|DateTimeImmutable $time;

    public function __construct(
        DateTime|DateTimeImmutable|string $time
    ) {
        if ($time instanceof DateTime || $time instanceof DateTimeImmutable) {
            $this->time = $time;
        } else {
            $dti = DateTimeImmutable::createFromFormat('H:i:s', $time);

            if ($dti instanceof DateTimeImmutable && $dti->format('H:i:s') === $time) {
                $this->time = $dti;
            }

            throw InvalidDatetimeStringException::make($time, 'H:i:s');
        }
    }

    public static function is(DateTime|DateTimeImmutable|string $time): Time
    {
        return new self($time);
    }

    public function toString(): string
    {
        return $this->time->format('H:i:s');
    }
}

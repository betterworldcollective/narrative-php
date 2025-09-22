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
            $dti = DateTimeImmutable::createFromFormat(TIME_FORMAT, $time);

            if ($dti instanceof DateTimeImmutable && $dti->format(TIME_FORMAT) === $time) {
                $this->time = $dti;
            }

            throw InvalidDatetimeStringException::make($time, TIME_FORMAT);
        }
    }

    public static function is(DateTime|DateTimeImmutable|string $time): Time
    {
        return new self($time);
    }

    public function toString(): string
    {
        return $this->time->format(TIME_FORMAT);
    }
}

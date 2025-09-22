<?php

namespace BetterWorld\Scribe\Support;

use BetterWorld\Scribe\Exceptions\InvalidDatetimeStringException;
use DateTimeImmutable;
use DateTimeInterface;

final readonly class Time
{
    private DateTimeInterface $time;

    public function __construct(
        DateTimeInterface|string $time
    ) {
        if ($time instanceof DateTimeInterface) {
            $this->time = $time;
        } else {
            $dti = DateTimeImmutable::createFromFormat(TIME_FORMAT, $time);

            if ($dti instanceof DateTimeImmutable && $dti->format(TIME_FORMAT) === $time) {
                $this->time = $dti;
            }

            throw InvalidDatetimeStringException::make($time, TIME_FORMAT);
        }
    }

    public static function is(DateTimeInterface|string $time): Time
    {
        return new self($time);
    }

    public function toString(): string
    {
        return $this->time->format(TIME_FORMAT);
    }
}

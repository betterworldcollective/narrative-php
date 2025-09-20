<?php

namespace BetterWorld\Scribe\Support;

use BetterWorld\Scribe\Exceptions\InvalidJsonException;

final class Json
{
    private string $json;

    /**
     * @param  mixed[]|string  $json
     */
    public function __construct(array|string $json)
    {
        if (is_array($json)) {
            $validJson = json_encode($json);

            if ($validJson === false) {
                throw new InvalidJsonException;
            }

            $this->json = $validJson;
        }

        if (is_string($json)) {
            json_decode($json);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new InvalidJsonException;
            }

            $this->json = $json;
        }
    }

    /**
     * @param  mixed[]|string  $json
     */
    public static function is(array|string $json): Json
    {
        return new self($json);
    }

    public function toString(): string
    {
        return $this->json;
    }
}

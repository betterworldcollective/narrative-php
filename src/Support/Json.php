<?php

namespace BetterWorld\Scribe\Support;

use BetterWorld\Scribe\Exceptions\InvalidJsonException;

final readonly class Json
{
    private string $json;

    /** @var mixed[] */
    private array $data;

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
            $this->data = $json;
        }

        if (is_string($json)) {
            /** @var mixed[] $data */
            $data = json_decode($json, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new InvalidJsonException;
            }

            $this->json = $json;
            $this->data = $data;
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

    /** @return mixed[] */
    public function toArray(): array
    {
        return $this->data;
    }
}

<?php

namespace BetterWorld\Scribe\Support;

use BetterWorld\Scribe\Exceptions\InvalidJsonException;
use BetterWorld\Scribe\Exceptions\NotListException;

final class ArrayList
{
    /** @var mixed[] */
    private array $list;

    /** @param mixed[] $list */
    public function __construct(array $list)
    {
        if (! array_is_list($list)) {
            throw new NotListException;
        }

        $validJson = json_encode($list);

        if ($validJson === false) {
            throw new InvalidJsonException;
        }

        $this->list = $list;
    }

    /** @param mixed[] $list */
    public static function is(array $list): ArrayList
    {
        return new self($list);
    }

    /** @return mixed[] */
    public function getList(): array
    {
        return $this->list;
    }
}

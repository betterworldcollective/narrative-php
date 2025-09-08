<?php

namespace Narrative;

use Narrative\Exceptions\MissingArrayKeyException;

/**
 * @param  mixed[]  $data
 *
 * @throws MissingArrayKeyException
 */
function array_value(array $data, string $key): mixed
{
    $keys = explode('.', $key);

    foreach ($keys as $k) {
        if (! is_array($data) || ! array_key_exists($k, $data)) {
            return throw new MissingArrayKeyException("Missing array key [$key]'.");
        }
        $data = $data[$k];
    }

    return $data;
}

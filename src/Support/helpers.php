<?php

namespace BetterWorld\Scribe\Support;

use BetterWorld\Scribe\Exceptions\MissingArrayKeyException;
use DateTimeImmutable;

const DATETIME_FORMAT = 'Y-m-d H:i:s';
const DATE_FORMAT = 'Y-m-d';
const TIME_FORMAT = 'H:i:s';
const TIMEZONE = 'UTC';

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
            return throw MissingArrayKeyException::make($key);
        }
        $data = $data[$k];
    }

    return $data;
}

/**
 * @param  string  $string  The original string to convert
 * @param  string  $delimiter  The delimiter to use
 * @param  string  $characters  The allowed characters to be used
 */
function delimited_case(string $string, string $delimiter = '_', string $characters = '/[^a-z0-9]+/'): string
{
    // Insert spaces before capital letters (to handle CamelCase / PascalCase)
    $string = (string) preg_replace('/([a-z])([A-Z])/', '$1 $2', $string);
    $string = (string) preg_replace('/([A-Z])([A-Z][a-z])/', '$1 $2', $string);

    // Convert to lowercase
    $string = strtolower($string);

    // Replace any non-alphanumeric characters with spaces
    $string = (string) preg_replace($characters, ' ', $string);

    // Trim spaces at start/end and replace remaining spaces with delimiter
    $string = trim($string);

    return (string) preg_replace('/\s+/', $delimiter, $string);
}

function between(string $string, ?string $start = null, ?string $end = null): string
{
    $result = $string;

    // If start delimiter is provided, cut after the *last* occurrence
    if ($start !== null) {
        $pos = strrpos($result, $start); // <— strrpos instead of strpos
        if ($pos !== false) {
            $result = substr($result, $pos + strlen($start));
        }
    }

    // If end delimiter is provided, cut before the first occurrence
    if ($end !== null) {
        $pos = strpos($result, $end);
        if ($pos !== false) {
            $result = substr($result, 0, $pos);
        }
    }

    return $result;
}

/**
 * @param  string  $string  The original string to convert
 */
function headline(string $string): string
{
    // Normalize all non-alphanumerics to spaces
    $s = (string) preg_replace('/[^a-zA-Z0-9]+/', ' ', $string);

    // Insert spaces between lowercase/number and Uppercase (helloWorld -> hello World)
    $s = (string) preg_replace('/([a-z0-9])([A-Z])/', '$1 $2', $s);
    // Insert spaces split acronym before Capital+lower (AABatterries -> AA Batterries)
    $s = (string) preg_replace('/([A-Z])([A-Z][a-z])/', '$1 $2', $s);

    // Collapse spaces and trim
    $s = trim((string) preg_replace('/\s+/', ' ', $s));
    if ($s === '') {
        return '';
    }

    // Capitalize words, preserving ALL-CAPS acronyms (len >= 2)
    $words = array_map(function ($w): string {
        if (preg_match('/^[A-Z0-9]{2,}$/', $w)) {
            return $w; // keep acronyms (AA, HTTP, S3)
        }

        return ucfirst(strtolower($w));
    }, explode(' ', $s));

    return implode(' ', $words);
}

function isValidDateTime(string $dateTimeString, string $format = DATETIME_FORMAT): bool
{
    $dt = DateTimeImmutable::createFromFormat($format, $dateTimeString);

    // Check both parsing success and exact format match
    return ($dt !== false) && ($dt->format($format) === $dateTimeString);
}

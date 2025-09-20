<?php

namespace BetterWorld\Scribe\Enums;

enum DataType: string
{
    case STRING = 'string';
    case INTEGER = 'integer';
    case FLOAT = 'float';
    case BOOLEAN = 'boolean';
    case LIST = 'list';
    case DATETIME = 'datetime';
    case DATE = 'date';
    case TIME = 'time';
    case JSON = 'json';
}

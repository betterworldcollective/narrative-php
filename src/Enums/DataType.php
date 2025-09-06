<?php

namespace Narrative\Enums;

enum DataType: string
{
    case String = 'string';
    case Integer = 'integer';
    case Float = 'float';
    case Boolean = 'boolean';
    case Date = 'date';
    case Time = 'time';
    case Datetime = 'datetime';
    case Null = 'null';
}

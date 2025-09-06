<?php

namespace Narrative\Contracts;

interface Publisher
{
    public function publish(Book $book): void;
}

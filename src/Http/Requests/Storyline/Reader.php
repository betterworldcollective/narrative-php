<?php

namespace BetterWorld\Scribe\Http\Requests\Storyline;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class Reader extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected int $page = 1
    ) {}

    public function resolveEndpoint(): string
    {
        return "/read?page={$this->page}";
    }
}

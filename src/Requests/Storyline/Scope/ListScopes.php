<?php

namespace BetterWorld\NarrativePhp\Requests\Storyline;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class ListScopes extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/scopes';
    }
}

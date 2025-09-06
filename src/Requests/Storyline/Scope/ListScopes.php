<?php

namespace Narrative\Requests\Storyline\Scope;

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

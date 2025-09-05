<?php

namespace BetterWorld\NarrativePhp\Requests\Storyline\Event;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class ListEvents extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/events';
    }
}

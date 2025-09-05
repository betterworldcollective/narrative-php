<?php

namespace BetterWorld\NarrativePhp\Requests\Storyline\Event;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteEvent extends Request
{
    public function __construct(protected string $slug) {}

    protected Method $method = Method::DELETE;

    public function resolveEndpoint(): string
    {
        return "/events/{$this->slug}";
    }
}

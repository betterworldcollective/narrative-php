<?php

namespace BetterWorld\NarrativePhp\Requests\Storyline\Event;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteEvent extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected string $slug
    ) {}

    public function resolveEndpoint(): string
    {
        return "/events/{$this->slug}";
    }
}

<?php

namespace BetterWorld\NarrativePhp\Requests\Storyline\Scope;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteScope extends Request
{
    public function __construct(
        protected string $slug
    ) {}

    protected Method $method = Method::DELETE;

    public function resolveEndpoint(): string
    {
        return "/scopes/{$this->slug}";
    }
}

<?php

namespace BetterWorld\NarrativePhp\Requests\Storyline\Scope;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteScope extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected string $slug
    ) {}

    public function resolveEndpoint(): string
    {
        return "/scopes/{$this->slug}";
    }
}

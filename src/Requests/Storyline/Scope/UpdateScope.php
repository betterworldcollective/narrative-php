<?php

namespace BetterWorld\NarrativePhp\Requests\Storyline\Scope;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class UpdateScope extends Request implements HasBody
{
    use HasJsonBody;

    public function __construct(protected string $slug, protected ?string $newSlug = null, protected ?string $newName = null, protected ?string $newContext = null) {}

    protected Method $method = Method::PATCH;

    public function resolveEndpoint(): string
    {
        return "/scopes/{$this->slug}";
    }

    protected function defaultBody(): array
    {
        if ($this->newSlug !== null) {
            $body['slug'] = $this->newSlug;
        }

        if ($this->newName !== null) {
            $body['name'] = $this->newName;
        }

        if ($this->newContext !== null) {
            $body['context'] = $this->newContext;
        }

        return $body;
    }
}

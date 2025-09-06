<?php

namespace Narrative\Requests\Storyline\Scope;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class UpdateScope extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PATCH;

    public function __construct(
        protected string $slug,
        protected ?string $newSlug = null,
        protected ?string $newName = null,
        protected ?string $newContext = null
    ) {}

    public function resolveEndpoint(): string
    {
        return "/scopes/{$this->slug}";
    }

    /** @return array<string,mixed> */
    protected function defaultBody(): array
    {
        $body = [];

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

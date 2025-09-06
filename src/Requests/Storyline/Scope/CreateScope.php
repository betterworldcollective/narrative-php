<?php

namespace Narrative\Requests\Storyline\Scope;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class CreateScope extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $name,
        protected string $context,
        protected ?string $slug = null
    ) {}

    public function resolveEndpoint(): string
    {
        return '/scopes';
    }

    /** @return array<string,mixed> */
    protected function defaultBody(): array
    {
        $body = [
            'name' => $this->name,
            'context' => $this->context,
        ];

        if ($this->slug !== null) {
            $body['slug'] = $this->slug;
        }

        return $body;
    }
}

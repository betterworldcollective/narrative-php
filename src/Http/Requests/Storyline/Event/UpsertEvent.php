<?php

namespace Narrative\Http\Requests\Storyline\Event;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class UpsertEvent extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    /**
     * @param  array<string,mixed>|null  $definition
     */
    public function __construct(
        protected string $name,
        protected string $context,
        protected ?array $definition = null,
        protected ?string $slug = null
    ) {}

    public function resolveEndpoint(): string
    {
        return '/events';
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

        if ($this->definition !== null) {
            $body['definition'] = $this->definition;
        }

        return $body;
    }
}

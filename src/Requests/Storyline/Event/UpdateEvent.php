<?php

namespace Narrative\Requests\Storyline\Event;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class UpdateEvent extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PATCH;

    /**
     * @param  array<string,mixed>|null  $newDefinition
     */
    public function __construct(
        protected string $slug,
        protected ?string $newSlug = null,
        protected ?string $newName = null,
        protected ?string $newContext = null,
        protected ?array $newDefinition = null
    ) {}

    public function resolveEndpoint(): string
    {
        return "/events/{$this->slug}";
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

        if ($this->newDefinition !== null) {
            $body['definition'] = $this->newDefinition;
        }

        return $body;
    }
}

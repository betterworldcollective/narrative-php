<?php

namespace BetterWorld\NarrativePhp\Requests\Storyline\Event;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class UpdateEvent extends Request implements HasBody
{
    use HasJsonBody;

    public function __construct(protected string $slug, protected ?string $newSlug = null, protected ?string $newName = null, protected ?string $newContext = null, protected ?array $newDefinition = null) {}

    protected Method $method = Method::PATCH;

    public function resolveEndpoint(): string
    {
        return "/events/{$this->slug}";
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

        if ($this->newDefinition !== null) {
            $body['definition'] = $this->newDefinition;
        }

        return $body;
    }
}

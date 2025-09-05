<?php

namespace BetterWorld\NarrativePhp\Requests\Storyline\Event;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class CreateEvent extends Request implements HasBody
{
    use HasJsonBody;

    public function __construct(protected string $name, protected string $context, protected ?array $definition = null, protected ?string $slug = null) {}

    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return '/events';
    }

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

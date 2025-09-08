<?php

namespace Narrative\Http;

use Narrative\Http\Requests\Storyline\Listener;
use Narrative\Http\Resources\Storyline\EventResource;
use Narrative\Http\Resources\Storyline\ScopeResource;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;

class Storyline extends Connector
{
    public function __construct(protected readonly string $baseUrl, protected readonly string $bearerToken) {}

    public function resolveBaseUrl(): string
    {
        return $this->baseUrl;
    }

    protected function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    protected function defaultAuth(): TokenAuthenticator
    {
        return new TokenAuthenticator($this->bearerToken);
    }

    public function events(): EventResource
    {
        return new EventResource($this);
    }

    public function scopes(): ScopeResource
    {
        return new ScopeResource($this);
    }

    /**
     * @param  mixed[]  $occurrences
     */
    public function listen(array $occurrences): bool
    {
        return $this->send(new Listener($occurrences))->successful();
    }
}

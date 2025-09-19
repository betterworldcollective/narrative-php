<?php

namespace BetterWorld\Scribe\Http;

use BetterWorld\Scribe\Http\Requests\Storyline\Reader;
use BetterWorld\Scribe\Http\Requests\Storyline\Writer;
use BetterWorld\Scribe\Http\Resources\Storyline\EventResource;
use BetterWorld\Scribe\Http\Resources\Storyline\ScopeResource;
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
    public function write(array $occurrences): bool
    {
        return $this->send(new Writer($occurrences))->successful();
    }

    /** @return mixed[] */
    public function read(int $page = 1): array
    {
        return $this->send(new Reader($page))->array();
    }
}

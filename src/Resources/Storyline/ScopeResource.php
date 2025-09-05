<?php

namespace BetterWorld\NarrativePhp\Resources\Storyline;

use BetterWorld\NarrativePhp\Requests\Storyline\ListScopes;
use BetterWorld\NarrativePhp\Requests\Storyline\Scope\CreateScope;
use BetterWorld\NarrativePhp\Requests\Storyline\Scope\DeleteScope;
use BetterWorld\NarrativePhp\Requests\Storyline\Scope\UpdateScope;
use Saloon\Http\BaseResource;

class ScopeResource extends BaseResource
{
    public function list(): array
    {
        return $this->connector->send(new ListScopes)->array();
    }

    public function create(string $name, string $context, ?string $slug = null): array
    {
        return $this->connector->send(new CreateScope($name, $context, $slug))->array();
    }

    public function update(string $slug, ?string $newSlug = null, ?string $newName = null, ?string $newContext = null): array
    {
        return $this->connector->send(new UpdateScope($slug, $newSlug, $newName, $newContext))->array();
    }

    public function delete(string $slug): void
    {
        $this->connector->send(new DeleteScope($slug));
    }

    public function values(): ScopeValueResource
    {
        return new ScopeValueResource($this->connector);
    }
}

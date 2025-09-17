<?php

namespace Narrative\Http\Resources\Storyline;

use Narrative\Http\Requests\Storyline\Scope\CreateScope;
use Narrative\Http\Requests\Storyline\Scope\DeleteScope;
use Narrative\Http\Requests\Storyline\Scope\ListScopes;
use Narrative\Http\Requests\Storyline\Scope\UpdateScope;
use Narrative\Http\Requests\Storyline\Scope\UpsertScope;
use Saloon\Http\BaseResource;

class ScopeResource extends BaseResource
{
    /** @return mixed[] */
    public function list(): array
    {
        return $this->connector->send(new ListScopes)->array();
    }

    /** @return mixed[] */
    public function create(
        string $name,
        string $context,
        ?string $slug = null
    ): array {
        return $this->connector->send(new CreateScope($name, $context, $slug))->array();
    }

    /** @return mixed[] */
    public function upsert(
        string $name,
        string $context,
        ?string $slug = null
    ): array {
        return $this->connector->send(new UpsertScope($name, $context, $slug))->array();
    }

    /** @return mixed[] */
    public function update(
        string $slug,
        ?string $newSlug = null,
        ?string $newName = null,
        ?string $newContext = null
    ): array {
        return $this->connector->send(new UpdateScope($slug, $newSlug, $newName, $newContext))->array();
    }

    public function delete(string $slug): bool
    {
        return $this->connector->send(new DeleteScope($slug))->successful();
    }

    public function values(): ScopeValueResource
    {
        return new ScopeValueResource($this->connector);
    }
}

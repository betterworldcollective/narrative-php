<?php

namespace Narrative\Http\Resources\Storyline;

use Narrative\Http\Requests\Storyline\Event\CreateEvent;
use Narrative\Http\Requests\Storyline\Event\DeleteEvent;
use Narrative\Http\Requests\Storyline\Event\ListEvents;
use Narrative\Http\Requests\Storyline\Event\UpdateEvent;
use Narrative\Http\Requests\Storyline\Event\UpsertEvent;
use Saloon\Http\BaseResource;

class EventResource extends BaseResource
{
    /** @return mixed[] */
    public function list(): array
    {
        return $this->connector->send(new ListEvents)->array();
    }

    /**
     * @param  array<string,mixed>  $definitions
     * @return mixed[]
     */
    public function create(
        string $name,
        string $context,
        ?array $definitions = null,
        ?string $slug = null
    ): array {
        return $this->connector->send(new CreateEvent($name, $context, $definitions, $slug))->array();
    }

    /**
     * @param  array<string,mixed>  $definitions
     * @return mixed[]
     */
    public function upsert(
        string $name,
        string $context,
        ?array $definitions = null,
        ?string $slug = null
    ): array {
        return $this->connector->send(new UpsertEvent($name, $context, $definitions, $slug))->array();
    }

    /**
     * @param  array<string,mixed>|null  $newDefinition
     * @return mixed[]
     */
    public function update(
        string $slug,
        ?string $newSlug = null,
        ?string $newName = null,
        ?string $newContext = null,
        ?array $newDefinition = null
    ): array {
        return $this->connector->send(new UpdateEvent($slug, $newSlug, $newName, $newContext, $newDefinition))->array();
    }

    public function delete(string $slug): void
    {
        $this->connector->send(new DeleteEvent($slug));
    }
}

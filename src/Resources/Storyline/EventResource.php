<?php

namespace Narrative\Resources\Storyline;

use Narrative\Requests\Storyline\Event\CreateEvent;
use Narrative\Requests\Storyline\Event\DeleteEvent;
use Narrative\Requests\Storyline\Event\ListEvents;
use Narrative\Requests\Storyline\Event\UpdateEvent;
use Saloon\Http\BaseResource;

class EventResource extends BaseResource
{
    /** @return mixed[] */
    public function list(): array
    {
        return $this->connector->send(new ListEvents)->array();
    }

    /**
     * @param  array<string,mixed>  $definition
     * @return mixed[]
     */
    public function create(
        string $name,
        string $context,
        ?array $definition = null,
        ?string $slug = null
    ): array {
        return $this->connector->send(new CreateEvent($name, $context, $definition, $slug))->array();
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

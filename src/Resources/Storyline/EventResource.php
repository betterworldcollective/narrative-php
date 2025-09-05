<?php

namespace BetterWorld\NarrativePhp\Resources\Storyline;

use BetterWorld\NarrativePhp\Requests\Storyline\Event\CreateEvent;
use BetterWorld\NarrativePhp\Requests\Storyline\Event\DeleteEvent;
use BetterWorld\NarrativePhp\Requests\Storyline\Event\ListEvents;
use BetterWorld\NarrativePhp\Requests\Storyline\Event\UpdateEvent;
use Saloon\Http\BaseResource;

class EventResource extends BaseResource
{
    public function list(): array
    {
        return $this->connector->send(new ListEvents)->array();
    }

    public function create(string $name, string $context, ?array $definition = null, ?string $slug = null): array
    {
        return $this->connector->send(new CreateEvent($name, $context, $definition, $slug))->array();
    }

    public function update(string $slug, ?string $newSlug = null, ?string $newName = null, ?string $newContext = null, ?array $definition = null): array
    {
        return $this->connector->send(new UpdateEvent($slug, $newSlug, $newName, $newContext, $definition))->array();
    }

    public function delete(string $slug): void
    {
        $this->connector->send(new DeleteEvent($slug));
    }
}

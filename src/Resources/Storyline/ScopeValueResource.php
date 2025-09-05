<?php

namespace BetterWorld\NarrativePhp\Resources\Storyline;

use BetterWorld\NarrativePhp\Requests\Storyline\Scope\Value\DeleteValues;
use BetterWorld\NarrativePhp\Requests\Storyline\Scope\Value\UpsertValues;
use Saloon\Http\BaseResource;

class ScopeValueResource extends BaseResource
{
    public function upsert(string $scope, array $scopes)
    {
        return $this->connector->send(new UpsertValues($scope, $scopes));
    }

    public function delete(string $scope, array $ids)
    {
        return $this->connector->send(new DeleteValues($scope, $ids));
    }
}

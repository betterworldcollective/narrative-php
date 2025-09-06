<?php

namespace Narrative\Resources\Storyline;

use Narrative\Requests\Storyline\Scope\Value\DeleteValues;
use Narrative\Requests\Storyline\Scope\Value\UpsertValues;
use Saloon\Http\BaseResource;

class ScopeValueResource extends BaseResource
{
    /**
     * @param  array{id:string,name:string}[]  $scopes
     * @return mixed[]
     */
    public function upsert(string $scope, array $scopes): array
    {
        return $this->connector->send(new UpsertValues($scope, $scopes))->array();
    }

    /**
     * @param  string[]  $ids
     */
    public function delete(string $scope, array $ids): bool
    {
        return $this->connector->send(new DeleteValues($scope, $ids))->successful();
    }
}

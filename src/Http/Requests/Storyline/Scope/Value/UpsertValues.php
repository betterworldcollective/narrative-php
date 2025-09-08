<?php

namespace Narrative\Http\Requests\Storyline\Scope\Value;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class UpsertValues extends Request implements HasBody
{
    use HasJsonBody;

    /**
     * @param  array{id:string,name:string}[]  $scopes
     */
    public function __construct(
        protected string $scope,
        protected array $scopes
    ) {}

    protected Method $method = Method::PUT;

    public function resolveEndpoint(): string
    {
        return "/scopes/{$this->scope}/values";
    }

    /** @return array<string,mixed> */
    protected function defaultBody(): array
    {
        return [
            'scopes' => $this->scopes,
        ];
    }
}

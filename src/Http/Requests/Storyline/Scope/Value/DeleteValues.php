<?php

namespace BetterWorld\Scribe\Http\Requests\Storyline\Scope\Value;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class DeleteValues extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::DELETE;

    /**
     * @param  string[]  $ids
     */
    public function __construct(
        protected string $scope, protected array $ids
    ) {}

    public function resolveEndpoint(): string
    {
        return "/scopes/{$this->scope}/values";
    }

    /** @return array<string,mixed> */
    protected function defaultBody(): array
    {
        return [
            'ids' => $this->ids,
        ];
    }
}

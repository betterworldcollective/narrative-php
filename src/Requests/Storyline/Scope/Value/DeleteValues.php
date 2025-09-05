<?php

namespace BetterWorld\NarrativePhp\Requests\Storyline\Scope\Value;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class DeleteValues extends Request implements HasBody
{
    use HasJsonBody;

    /**
     * @param  string[]  $scopes
     */
    public function __construct(protected string $scope, protected array $ids) {}

    protected Method $method = Method::DELETE;

    public function resolveEndpoint(): string
    {
        return "/scopes/{$this->scope}/values";
    }

    protected function defaultBody(): array
    {
        return [
            'ids' => $this->ids,
        ];

    }
}

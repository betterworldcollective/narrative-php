<?php

namespace Narrative\Requests\Storyline;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class Listener extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  mixed[]  $occurrences
     */
    public function __construct(
        protected array $occurrences
    ) {}

    public function resolveEndpoint(): string
    {
        return '/listen';
    }

    /**
     * @return array<string,mixed[]>
     */
    protected function defaultBody(): array
    {
        return [
            'events' => $this->occurrences,
        ];
    }
}

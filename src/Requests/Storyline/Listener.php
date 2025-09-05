<?php

namespace BetterWorld\NarrativePhp\Requests\Storyline;

use Saloon\Contracts\Body\HasBody;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class Listener extends Request implements HasBody
{
    use HasJsonBody;

    public function __construct(protected array $occurrences) {}

    public function resolveEndpoint(): string
    {
        return '/listen';
    }

    protected function defaultBody(): array
    {
        return [
            'events' => $this->occurrences,
        ];
    }
}

<?php

namespace Narrative;

use Narrative\Contracts\Narrative;
use Narrative\Contracts\Publisher;
use Narrative\Contracts\Scope;

final class Registrar
{
    protected NarrativeService $narrativeService;

    /**
     * @param  array{
     *     publishers: array<string,array{class:class-string<Publisher>,options:array<string,mixed>}>,
     *     default_book: string,
     *     books: array<string, array{publishers: string[]}>,
     *     auto_publish: bool
     * }  $config
     */
    public function __construct(
        array $config
    ) {
        $this->narrativeService = new NarrativeService($config);
    }

    /**
     * @param  array{
     *     publishers: array<string,array{class:class-string<Publisher>,options:array<string,mixed>}>,
     *     default_book: string,
     *     books: array<string, array{publishers: string[]}>,
     *     auto_publish: bool
     * }  $config
     */
    public static function make(array $config): static
    {
        return new self($config);
    }

    /**
     * @param  class-string<Narrative>[]  $events
     */
    public function registerEvents(array $events, string $publisher): static
    {
        foreach ($events as $event) {
            $this->narrativeService
                ->getStoryline($publisher)
                ->events()
                ->upsert($event::name(), $event::context(), $event::definitions(), $event::key());
        }

        return $this;
    }

    /**
     * @param  class-string<Scope>[]  $scopes
     */
    public function registerScopes(array $scopes, string $publisher): static
    {
        foreach ($scopes as $scope) {
            $scopeName = $scope::name();

            $storylineConnector = $this->narrativeService
                ->getStoryline($publisher);

            $storylineConnector
                ->scopes()
                ->upsert($scopeName, $scope::context());

            $storylineConnector
                ->scopes()
                ->values()
                ->upsert($scopeName, (new $scope)->values());
        }

        return $this;
    }
}

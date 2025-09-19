<?php

namespace BetterWorld\Scribe;

use BetterWorld\Scribe\Contracts\Narrative;
use BetterWorld\Scribe\Contracts\Publisher;

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
        protected array $config
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
    public static function make(array $config): Registrar
    {
        return new Registrar($config);
    }

    /**
     * @param  class-string<Narrative>[]  $events
     */
    public function registerEvents(array $events): static
    {
        foreach ($events as $event) {
            foreach ($this->narrativeService->bookPublisher(...$event::books()) as $publisher) {
                $this->narrativeService
                    ->getStoryline($publisher)
                    ?->events()
                    ->upsert($event::name(), $event::context(), $event::definitions(), $event::key());
            }
        }

        return $this;
    }

    /**
     * @param  class-string<Scope>[]  $scopes
     */
    public function registerScopes(array $scopes): static
    {
        foreach ($scopes as $scope) {
            $scopeKey = $scope::key();
            $scopeName = $scope::name();

            foreach ($this->narrativeService->bookPublisher(...$scope::books()) as $publisher) {
                $storylineConnector = $this->narrativeService
                    ->getStoryline($publisher);

                if ($storylineConnector === null) {
                    continue;
                }

                $storylineConnector
                    ->scopes()
                    ->upsert($scopeName, $scope::context(), $scopeKey);

                $storylineConnector
                    ->scopes()
                    ->values()
                    ->upsert($scopeKey, (new $scope)->values());
            }
        }

        return $this;
    }
}

<?php

namespace Narrative;

use Narrative\Contracts\Publisher;

final class Registrar implements Contracts\Registrar
{
    public function __construct(
        protected NarrativeService $narrativeService
    ) {}

    /**
     * @param  array{
     *     host:string,
     *     default_storyline:string,
     *     storylines: array<string, array{id:string, token:string}>,
     *     default_publisher: string|string[],
     *     publishers: array<string, array{class:class-string<Publisher>, option:array<string,mixed>}>,
     *     auto_publish: bool
     * }  $config
     */
    public static function make(array $config): static
    {
        return new self(new NarrativeService($config));
    }

    public function registerEvents(array $events): static
    {
        foreach ($events as $event) {
            foreach ($event::storylines() as $storyline) {
                $this->narrativeService
                    ->getStorylineConnector($storyline)
                    ->events()
                    ->create($event::name(), $event::context(), $event::definitions(), $event::slug());
            }
        }

        return $this;
    }

    public function registerScopes(array $scopes): static
    {
        foreach ($scopes as $scope) {
            $scopeName = $scope::name();

            foreach ($scope::storylines() as $storyline) {
                $storylineConnector = $this->narrativeService
                    ->getStorylineConnector($storyline);

                $storylineConnector
                    ->scopes()
                    ->create($scopeName, $scope::context());

                $storylineConnector
                    ->scopes()
                    ->values()
                    ->upsert($scopeName, (new $scope)->values());
            }
        }

        return $this;
    }
}

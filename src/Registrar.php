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
     *     host:string|null,
     *     default_storyline:string|null,
     *     storylines: array<string, array{id:string|null, token:string|null}>|null,
     *     default_publisher: class-string<Publisher>|null,
     *     publishers: array<string, class-string<Publisher>>,
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

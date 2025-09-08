<?php

namespace Narrative;

final class Registrar implements Contracts\Registrar
{
    public function __construct(
        protected NarrativeService $narrativeService
    ) {}

    public function registerEvents(array $events): void
    {
        foreach ($events as $event) {
            foreach ($event::storylines() as $storyline) {
                $this->narrativeService
                    ->getStorylineConnector($storyline)
                    ->events()
                    ->create($event::name(), $event::context(), $event::definitions(), $event::slug());
            }
        }
    }

    public function registerScopes(array $scopes): void
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
    }
}

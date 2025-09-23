<?php

namespace BetterWorld\Scribe\Publishers;

use BetterWorld\Scribe\Contracts\Book;
use BetterWorld\Scribe\Contracts\Metadata;
use BetterWorld\Scribe\Contracts\Narrative;
use BetterWorld\Scribe\Contracts\Publisher;
use BetterWorld\Scribe\Exceptions\MissingArrayKeyException;
use Exception;
use Mixpanel;

use Producers_MixpanelGroups;
use function BetterWorld\Scribe\Support\array_value;

final readonly class MixpanelPublisher implements Publisher
{
    private Mixpanel $mixpanel;

    /**
     * @param  array<string,mixed>  $options
     * @throws MissingArrayKeyException
     */
    public function __construct(
        private string $name,
        private array $options = []
    ) {
        /** @var string $token */
        $token = array_value($this->options, 'token');

        $this->mixpanel = Mixpanel::getInstance($token);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function publish(Book $book): bool
    {
        foreach ($book->read() as $narrative) {
            try {
                $this->publishNarrative($narrative);
            } catch (Exception $e) {
                error_log('Mixpanel tracking failed: ' . $e->getMessage());
            }
        }

        return true;
    }

    /**
     * @param Narrative $narrative
     */
    private function publishNarrative($narrative): void
    {
        $metadata = $narrative instanceof Metadata ? $narrative->metadata() : [];
        $trackProperties = $narrative->values();

        $userId = $this->handleUserMetadata($metadata);
        $orgId = $this->handleOrganizationMetadata($metadata);

        $this->addContextToTrackProperties($trackProperties, $userId, $orgId);
        $this->trackEvent($narrative, $trackProperties);
    }

    /**
     * @param array<string, mixed> $metadata
     */
    private function handleUserMetadata(array $metadata): ?string
    {
        if (!isset($metadata['user']) || !is_array($metadata['user'])) {
            return null;
        }

        $userData = $metadata['user'];
        $userId = $userData['id'] ?? null;

        if (!is_string($userId)) {
            return null;
        }

        $this->mixpanel->identify(user_id: $userId);

        if (isset($userData['properties']) && is_array($userData['properties'])) {
            $this->mixpanel->people->setOnce($userId, $userData['properties']);
        }

        return $userId;
    }

    /**
     * @param array<string, mixed> $metadata
     */
    private function handleOrganizationMetadata(array $metadata): ?string
    {
        if (!isset($metadata['organization']) || !is_array($metadata['organization'])) {
            return null;
        }

        $orgData = $metadata['organization'];
        $orgId = $orgData['id'] ?? null;

        if (!is_string($orgId)) {
            return null;
        }

        if (isset($orgData['properties']) && is_array($orgData['properties'])) {
            /** @var Producers_MixpanelGroups $group */
            $group = $this->mixpanel->group;
            $group->setOnce('organization_id', $orgId, $orgData['properties']);
        }

        return $orgId;
    }

    /**
     * @param array<string, mixed> $trackProperties
     */
    private function addContextToTrackProperties(array &$trackProperties, ?string $userId, ?string $orgId): void
    {
        if ($userId) {
            $trackProperties['distinct_id'] = $userId;
        }

        if ($orgId) {
            $trackProperties['organization_id'] = $orgId;
            $trackProperties['$groups'] = ['organization_id' => $orgId];
        }
    }

    /**
     * @param Narrative $narrative
     * @param array<string, mixed> $trackProperties
     */
    private function trackEvent(Narrative $narrative, array $trackProperties): void
    {
        $this->mixpanel->track(
            event: $narrative::key(),
            properties: $trackProperties
        );
    }
}

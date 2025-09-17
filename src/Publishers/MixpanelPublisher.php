<?php

namespace Narrative\Publishers;

use Exception;
use InvalidArgumentException;
use Mixpanel;
use Narrative\Contracts\Book;
use Narrative\Contracts\Publisher;
use Narrative\Exceptions\MissingArrayKeyException;
use Narrative\NarrativeService;
use Narrative\ScopedNarrative;

use function Narrative\Support\array_value;

class MixpanelPublisher implements Publisher
{
    protected ?Mixpanel $mixpanel = null;

    public function __construct(
        protected NarrativeService $narrativeService
    ) {}

    /**
     * @throws MissingArrayKeyException
     */
    protected function getMixpanel(): Mixpanel
    {
        if ($this->mixpanel === null) {
            $token = array_value($this->narrativeService->getConfig(), 'mix_panel_token');
            
            if (!$token) {
                throw new InvalidArgumentException('Mixpanel token is required but not configured. Set mix_panel_token in config.');
            }

            $this->mixpanel = Mixpanel::getInstance(token: $token);
        }

        return $this->mixpanel;
    }

    /**
     * @throws MissingArrayKeyException
     */
    public function publish(Book $book): bool
    {
        $mixpanel = $this->getMixpanel();

        foreach ($book->storylines() as $storyline) {
            foreach ($book->read($storyline) as $narrative) {
                $scopes = null;

                if ($narrative instanceof ScopedNarrative) {
                    $scopes = $narrative->scopes;
                    $narrative = $narrative->narrative;
                }

                try {
                    if ($scopes && isset($scopes['user_id'])) {
                        $userId = $scopes['user_id'];
                        $mixpanel->identify(user_id: $userId);
                        
                        // Use properties directly from scopes
                        if (isset($scopes['properties']) && is_array($scopes['properties'])) {
                            $mixpanel->people->setOnce($userId, $scopes['properties']);
                        }
                    }

                    $eventProperties = array_merge(
                        $narrative->values(),
                        [
                            'occurred_at' => $narrative->occurredAt(),
                        ]
                    );

                    $mixpanel->track(
                        event: $narrative->slug(),
                        properties: $eventProperties
                    );

                } catch (Exception $e) {
                    error_log("Mixpanel tracking failed: " . $e->getMessage());
                }
            }
        }

        return true;
    }
}

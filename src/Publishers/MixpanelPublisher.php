<?php

namespace Narrative\Publishers;

use Exception;
use Mixpanel;
use Narrative\Contracts\Book;
use Narrative\Contracts\Publisher;
use Narrative\NarrativeService;
use Narrative\ScopedNarrative;

use function Narrative\Support\array_value;

class MixpanelPublisher implements Publisher
{
    protected Mixpanel $mixpanel;

    /**
     * @param  array<string,mixed>  $options
     */
    public function __construct(
        protected string $name,
        protected NarrativeService $narrativeService,
        protected array $options = []
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
            $narrative = $narrative instanceof ScopedNarrative ? $narrative->narrative : $narrative;

            $metadata = $narrative->metadata();

            try {
                if (isset($metadata['user_id']) && is_string($metadata['user_id'])) {
                    $userId = $metadata['user_id'];
                    $this->mixpanel->identify(user_id: $userId);

                    if (isset($metadata['properties']) && is_array($metadata['properties'])) {
                        $this->mixpanel->people->setOnce($userId, $metadata['properties']);
                    }
                }

                $this->mixpanel->track(
                    event: (string) $narrative::key(),
                    properties: $narrative->values()
                );

            } catch (Exception $e) {
                error_log('Mixpanel tracking failed: '.$e->getMessage());
            }
        }

        return true;
    }
}

<?php

namespace BetterWorld\Scribe\Publishers;

use BetterWorld\Scribe\Contracts\Book;
use BetterWorld\Scribe\Contracts\Metadata;
use BetterWorld\Scribe\Contracts\Publisher;
use Exception;
use Mixpanel;

use function BetterWorld\Scribe\Support\array_value;

final readonly class MixpanelPublisher implements Publisher
{
    private Mixpanel $mixpanel;

    /**
     * @param  array<string,mixed>  $options
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
            $metadata = $narrative instanceof Metadata ? $narrative->metadata() : [];

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

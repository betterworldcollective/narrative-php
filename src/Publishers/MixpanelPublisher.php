<?php

namespace BetterWorld\Scribe\Publishers;

use BetterWorld\Scribe\Contracts\Book;
use BetterWorld\Scribe\Contracts\Publisher;
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
            $this->mixpanel->track(
                event: $narrative::key(),
                properties: $narrative->values()
            );
        }

        return true;
    }
}

<?php

use BetterWorld\Scribe\Contracts\Book;
use BetterWorld\Scribe\Contracts\Metadata;
use BetterWorld\Scribe\Narrative;
use BetterWorld\Scribe\Publishers\MixpanelPublisher;

function createMixpanelPublisher(string $token = 'test-token'): MixpanelPublisher
{
    return new MixpanelPublisher('test-mixpanel', ['token' => $token]);
}

/**
 * @param  array<string, mixed>  $values
 * @param  array<string, mixed>|null  $metadata
 * @return \BetterWorld\Scribe\Contracts\Narrative&\BetterWorld\Scribe\Contracts\Metadata
 */
function createTestNarrative(array $values, ?array $metadata = null)
{
    return new class($values, $metadata) extends Narrative implements Metadata
    {
        /**
         * @param  array<string, mixed>  $eventValues
         * @param  array<string, mixed>|null  $eventMetadata
         */
        public function __construct(
            private array $eventValues,
            private ?array $eventMetadata
        ) {}

        public static function key(): string
        {
            return 'test_event';
        }

        /**
         * @return array<string, mixed>
         */
        public function values(): array
        {
            return $this->eventValues;
        }

        /**
         * @return array<string, mixed>
         */
        public function metadata(): array
        {
            return $this->eventMetadata ?? [];
        }
    };
}

/**
 * @param  array<\BetterWorld\Scribe\Contracts\Narrative>  $narratives
 */
function createTestBook(array $narratives): Book
{
    return new class($narratives) implements Book
    {
        /**
         * @param  array<\BetterWorld\Scribe\Contracts\Narrative>  $narratives
         */
        public function __construct(private array $narratives) {}

        public function name(): string
        {
            return 'test-book';
        }

        /**
         * @return array<\BetterWorld\Scribe\Contracts\Narrative>
         */
        public function read(): array
        {
            return $this->narratives;
        }

        public function write(\BetterWorld\Scribe\Contracts\Narrative $narrative): static
        {
            $this->narratives[] = $narrative;

            return $this;
        }

        public function publishers(): array
        {
            return [];
        }

        public function publish(): void {}
    };
}

test('mixpanel publisher has a name', function (): void {
    $publisher = createMixpanelPublisher();

    expect($publisher->name())->toBe('test-mixpanel');
});

test('mixpanel publisher creates mixpanel instance with token', function (): void {
    $publisher = createMixpanelPublisher();

    expect($publisher)->toBeInstanceOf(MixpanelPublisher::class);
});

test('mixpanel publisher publishes narratives without metadata', function (): void {
    $publisher = createMixpanelPublisher();
    $narrative = createTestNarrative(['property1' => 'value1', 'property2' => 'value2']);
    $book = createTestBook([$narrative]);

    $result = $publisher->publish($book);

    expect($result)->toBeTrue();
});

test('mixpanel publisher publishes narratives with user metadata', function (): void {
    $publisher = createMixpanelPublisher();
    $metadata = [
        'user' => [
            'id' => 'USER5',
            'properties' => [
                '$name' => 'Tien Le',
                'email' => 'tien@example.com',
            ],
        ],
    ];
    $narrative = createTestNarrative(['action' => 'login'], $metadata);
    $book = createTestBook([$narrative]);

    $result = $publisher->publish($book);

    expect($result)->toBeTrue();
});

test('mixpanel publisher publishes narratives with organization metadata', function (): void {
    $publisher = createMixpanelPublisher();
    $metadata = [
        'organization' => [
            'id' => 'COMP7',
            'properties' => [
                'Name' => 'BetterWorld',
                'Plan' => 'Enterprise',
            ],
        ],
    ];
    $narrative = createTestNarrative(['action' => 'org_created'], $metadata);
    $book = createTestBook([$narrative]);

    $result = $publisher->publish($book);

    expect($result)->toBeTrue();
});

test('mixpanel publisher publishes narratives with both user and organization metadata', function (): void {
    $publisher = createMixpanelPublisher();
    $metadata = [
        'user' => [
            'id' => 'USER5',
            'properties' => [
                '$name' => 'Tien Le',
                'organization_id' => 'COMP7',
            ],
        ],
        'organization' => [
            'id' => 'COMP7',
            'properties' => [
                'Name' => 'BetterWorld',
            ],
        ],
    ];
    $narrative = createTestNarrative(['action' => 'login'], $metadata);
    $book = createTestBook([$narrative]);

    $result = $publisher->publish($book);

    expect($result)->toBeTrue();
});

test('mixpanel publisher handles multiple narratives in book', function (): void {
    $publisher = createMixpanelPublisher();
    $narrative1 = createTestNarrative(['data' => 'value1']);
    $narrative2 = createTestNarrative(['data' => 'value2']);
    $book = createTestBook([$narrative1, $narrative2]);

    $result = $publisher->publish($book);

    expect($result)->toBeTrue();
});

test('mixpanel publisher handles empty book', function (): void {
    $publisher = createMixpanelPublisher();
    $book = createTestBook([]);

    $result = $publisher->publish($book);

    expect($result)->toBeTrue();
});

test('mixpanel publisher handles exceptions gracefully', function (): void {
    $publisher = createMixpanelPublisher('invalid-token');
    $narrative = createTestNarrative(['data' => 'value']);
    $book = createTestBook([$narrative]);

    $result = $publisher->publish($book);

    expect($result)->toBeTrue();
});

<?php

use const BetterWorld\Scribe\Support\DATE_FORMAT;
use const BetterWorld\Scribe\Support\TIME_FORMAT;

use BetterWorld\Scribe\Attributes\Books;
use BetterWorld\Scribe\Attributes\Key;
use BetterWorld\Scribe\Attributes\Name;
use BetterWorld\Scribe\Exceptions\MissingContextException;
use BetterWorld\Scribe\Narratives\Narrative;
use BetterWorld\Scribe\Support\ArrayList;
use BetterWorld\Scribe\Support\Date;
use BetterWorld\Scribe\Support\DateTime;
use BetterWorld\Scribe\Support\Json;
use BetterWorld\Scribe\Support\Time;
use Tests\Fixtures\FixtureTestedNarrative;

test(' a narrative book defaults to null', function (): void {
    $books = FixtureTestedNarrative::books();
    expect($books)->toBe([null]);
});

test(' a narrative can define books', function (): void {
    $narrative = new
    #[Books('dev', 'test')]
    class extends Narrative {};

    expect($narrative::books())->toBe(['dev', 'test']);
});

test('a narrative has a name', function (): void {
    $name = FixtureTestedNarrative::name();
    expect($name)->toBe('Fixture Tested');
});

test('a narrative can define a name', function (): void {
    $narrative = new
    #[Name('A custom test name')]
    class extends Narrative {};

    expect($narrative::name())->toBe('A custom test name');
});

test('a narrative has a key', function (): void {
    $key = FixtureTestedNarrative::key();
    expect($key)->toBe('fixture_tested');
});

test('a narrative can define a key', function (): void {
    $narrative = new
    #[Key('my-custom key')]
    class extends Narrative {};

    expect($narrative::key())->toBe('my_custom_key');
});

test(' a narrative has a context', function (): void {
    $context = FixtureTestedNarrative::context();
    expect($context)->toBe('This is a test');
});

test('a narrative requires a context', function (): void {
    $narrative = new class('John Doe') extends Narrative
    {
        public function __construct(public string $name) {}
    };

    expect(fn (): string => $narrative::context())->toThrow(MissingContextException::class);
    expect(fn (): array => $narrative::definitions())->toThrow(MissingContextException::class);
});

test('a narrative has no framing by default', function (): void {
    $narrative = new class extends Narrative {};

    expect($narrative->framing())->toBe(null);
});

test(' a narrative can frame the event message', function (): void {
    $narrative = new class('John Doe') extends Narrative
    {
        public function __construct(public string $name) {}

        public function framing(): string
        {
            return "This is a custom framing. By: {$this->name}";
        }
    };

    expect($narrative->framing())->toBe('This is a custom framing. By: John Doe');
});

test('a narrative can generate a definitions array', function (): void {
    $definition = FixtureTestedNarrative::definitions();

    expect($definition)->toBe([
        'name' => [
            'type' => 'string',
            'nullable' => false,
            'context' => 'This is a string field.',
        ],
        'count' => [
            'type' => 'integer',
            'nullable' => false,
            'context' => 'This is an integer field.',
        ],
        'amount' => [
            'type' => 'float',
            'nullable' => false,
            'context' => 'This is a float field.',
        ],
        'is_active' => [
            'type' => 'boolean',
            'nullable' => false,
            'context' => 'This is a boolean field.',
        ],
        'tags' => [
            'type' => 'list',
            'nullable' => false,
            'context' => 'This is a list field.',
        ],
        'time' => [
            'type' => 'time',
            'nullable' => false,
            'context' => 'This is a time field.',
        ],
        'date' => [
            'type' => 'date',
            'nullable' => false,
            'context' => 'This is a date field.',
        ],
        'json' => [
            'type' => 'json',
            'nullable' => true,
            'context' => 'This is a json field.',
        ],
        'datetime_created' => [
            'type' => 'datetime',
            'nullable' => true,
            'context' => 'This is a datetime field.',
        ],
    ]);

});

test('a narrative can generate a values array', function (): void {
    $narrative = new FixtureTestedNarrative(
        'John Doe',
        123,
        20.25,
        false,
        ArrayList::is(['test', 'narrative']),
        Time::is(\DateTime::createFromFormat(TIME_FORMAT, '05:25:50')),
        Date::is(DateTimeImmutable::createFromFormat(DATE_FORMAT, '2025-07-08')),
        Json::is(['from' => 'this', 'to' => 'that']),
        DateTime::is('2025-03-02 01:10:11'),
    );

    expect($narrative->values())->toBe([
        'name' => 'John Doe',
        'count' => 123,
        'amount' => 20.25,
        'is_active' => false,
        'tags' => ['test', 'narrative'],
        'time' => '05:25:50',
        'date' => '2025-07-08',
        'json' => '{"from":"this","to":"that"}',
        'datetime_created' => '2025-03-02 01:10:11',
    ]);
});

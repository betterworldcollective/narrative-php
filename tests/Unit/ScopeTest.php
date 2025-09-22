<?php

use BetterWorld\Scribe\Exceptions\MissingContextException;
use BetterWorld\Scribe\Narratives\Scopes\UserScope;
use BetterWorld\Scribe\Scope;

test('a scope has a key', function (): void {
    expect(UserScope::key())->toBe('user_scope');
});

test('a scope can define a key', function (): void {
    $scope = new class(1, 'test') extends Scope
    {
        protected static string $key = 'test:custom_key';
    };

    expect($scope::key())->toBe('test:custom_key');
});

test('a scope has a label', function (): void {
    expect(UserScope::label())->toBe('User Scope');
});

test('a scope can define a label', function (): void {
    $scope = new class(1, 'test') extends Scope
    {
        protected static string $label = 'A Custom Label';
    };

    expect($scope::label())->toBe('A Custom Label');
});

test('a scope has a context', function (): void {
    expect(UserScope::context())->toBe('The ID of the user who triggered the event.');
});

test('a scope requires context', function (): void {
    $scope = new class(1, 'test') extends Scope {};

    expect(fn (): string => $scope::context())->toThrow(MissingContextException::class);
});

test('a scope has books', function (): void {
    expect(UserScope::books())->toBe(['main']);
});

test('a scope can define a books', function (): void {
    $scope = new class(1, 'test') extends Scope
    {
        protected static array $books = ['dev', 'test'];
    };

    expect($scope::books())->toBe(['dev', 'test']);
});

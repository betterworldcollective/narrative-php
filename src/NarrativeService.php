<?php

namespace BetterWorld\Scribe;

use BetterWorld\Scribe\Contracts\Publisher;
use BetterWorld\Scribe\Exceptions\InvalidPublisherException;
use BetterWorld\Scribe\Http\Storyline;

use function BetterWorld\Scribe\Support\array_value;

final class NarrativeService
{
    /** @var array<string, Publisher> */
    protected array $publishers;

    /** @var array<string, \BetterWorld\Scribe\Contracts\Book> */
    protected array $books;

    /**
     * @param  array{
     *     publishers: array<string,array{class:class-string<Publisher>,options:array<string,mixed>}>,
     *     default_book: string,
     *     books: array<string, array{publishers: string[]}>,
     *     auto_publish: bool
     * }  $config
     */
    public function __construct(
        protected array $config
    ) {
        $this->makePublishers();
        $this->makeBooks();
    }

    private function makePublishers(): void
    {
        /** @var array<string,array{class:class-string<Publisher>,options:array<string,mixed>}> $publishers */
        $publishers = array_value($this->config, 'publishers');

        foreach ($publishers as $key => $config) {
            /** @var class-string<Publisher> $class */
            $class = array_value($config, 'class');

            if (! in_array(Publisher::class, class_implements($class))) {
                throw InvalidPublisherException::make($class);
            }

            /** @var array<string,mixed> $options */
            $options = array_value($config, 'options');

            $this->publishers[$key] = new $class($key, $this, $options);
        }
    }

    private function makeBooks(): void
    {
        /** @var array<string, array{publishers: string[]}> $books */
        $books = array_value($this->config, 'books');

        foreach ($books as $key => $config) {
            $publishers = [];

            /** @var string[] $publishersKeys */
            $publishersKeys = array_value($config, 'publishers');

            foreach ($publishersKeys as $publisher) {
                $publishers[] = $this->publishers[$publisher];
            }

            $this->books[$key] = new Book($key, $publishers);
        }
    }

    /** @return Publisher[] */
    public function getPublishers(): array
    {
        return $this->publishers;
    }

    /** @return array<string, \BetterWorld\Scribe\Contracts\Book> */
    public function getBooks(): array
    {
        return $this->books;
    }

    public function getPublisher(string $publisher): Publisher
    {
        return $this->publishers[$publisher];
    }

    public function getBook(?string $book = null): \BetterWorld\Scribe\Contracts\Book
    {
        /** @var string $default */
        $default = array_value($this->config, 'default_book');

        return $this->books[$book ?? $default];
    }

    public function shouldAutoPublish(): bool
    {
        return (bool) array_value($this->config, 'auto_publish');
    }

    public function getStoryline(string $publisher): Storyline
    {
        /** @var string $host */
        $host = array_value($this->config, "publishers.{$publisher}.options.host");

        /** @var string $id */
        $id = array_value($this->config, "publishers.{$publisher}.options.storyline_id");

        /** @var string $token */
        $token = array_value($this->config, "publishers.{$publisher}.options.storyline_token");

        return new Storyline("{$host}/storylines/{$id}", $token);
    }
}

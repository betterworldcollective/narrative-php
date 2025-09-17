<?php

namespace Narrative;

use Narrative\Contracts\Publisher;
use Narrative\Http\Storyline;

use function Narrative\Support\array_value;

final class NarrativeService
{
    /**
     * @param  array{
     *     host:string,
     *     default_storyline:string,
     *     storylines: array<string, array{id:string, token:string}>,
     *     default_publisher: string|string[],
     *     publishers: array<string, array{class:class-string<Publisher>, option:array<string,mixed>}>,
     *     auto_publish: bool
     * }  $config
     */
    public function __construct(
        protected array $config
    ) {}

    public function getHost(): string
    {
        /** @var string $host */
        $host = array_value($this->config, 'host');

        return $host;
    }

    /**
     * @return array{name:string, url:string, token:string}
     */
    private function getStoryline(?string $name = null): array
    {
        /** @var string $name */
        $name = $name === null ? array_value($this->config, 'default_storyline') : $name;

        $host = $this->getHost();

        /** @var string $id */
        $id = array_value($this->config, "storylines.{$name}.id");

        /** @var string $token */
        $token = array_value($this->config, "storylines.{$name}.token");

        return [
            'name' => $name,
            'url' => "{$host}/storylines/{$id}",
            'token' => $token,
        ];
    }

    public function getStorylineConnector(?string $name = null): Storyline
    {
        /**
         * @var array{name:string, url:string, token:string} $storyline
         */
        $storyline = $this->getStoryline($name);

        return new Storyline($storyline['url'], $storyline['token']);
    }

    /** @return string|string[] */
    public function getDefaultPublisher(): string|array
    {
        /** @var string|string[] $default */
        $default = array_value($this->config, 'default_publisher');

        return $default;
    }

    public function getPublisher(string $name): Publisher
    {
        /** @var class-string<Publisher> $class */
        $class = array_value($this->config, "publishers.{$name}.class");

        /** @var array<string,mixed> $options */
        $options = array_value($this->config, "publishers.{$name}.options");

        /** @var Publisher $publisher */
        $publisher = new $class($this, $options);

        return $publisher;
    }

    /**
     * @param  string[]  $names
     * @return Publisher[]
     */
    public function getPublishers(array $names): array
    {
        $publishers = [];

        foreach ($names as $name) {
            $publishers[] = $this->getPublisher($name);
        }

        return $publishers;
    }

    public function shouldAutoPublish(): bool
    {
        return (bool) array_value($this->config, 'auto_publish');
    }
}

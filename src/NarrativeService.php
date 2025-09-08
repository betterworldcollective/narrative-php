<?php

namespace Narrative;

use Narrative\Contracts\Publisher;

final class NarrativeService
{
    /**
     * @param  array{
     *     host:string|null,
     *     default_storyline:string|null,
     *     storylines: array<string, array{id:string|null, token:string|null}>|null,
     *     default_publisher: class-string<Publisher>|null,
     *     publishers: array<string, class-string<Publisher>>,
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
    public function getStoryline(?string $name = null): array
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

    public function getPublisher(?string $name = null): Publisher
    {
        /** @var class-string<Publisher> $name */
        $name = $name === null ? array_value($this->config, 'default_publisher') : $name;

        /** @var Publisher $publisher */
        $publisher = new $name;

        return $publisher;
    }

    public function shouldAutoPublish(): bool
    {
        return (bool) array_value($this->config, 'auto_publish');
    }
}

<?php

namespace Narrative;

use Narrative\Exceptions\MissingConfigurationException;

final class NarrativeService
{
    /**
     * @param  array{host:string|null, default:string|null, storylines: array<string, array{id:string|null, token:string|null}>|null}  $config
     */
    public function __construct(
        protected array $config
    ) {}

    public function getHost(): string
    {
        return $this->config['host'] ?? throw new MissingConfigurationException('Host does not exist.');
    }

    /**
     * @return array{name:string, url:string, token:string}
     */
    public function getStoryline(?string $name = null): array
    {
        if ($name === null) {
            $name = $this->config['default'] ?? throw new MissingConfigurationException('[Default storyline does not exist.');
        }

        $storylineConfig = $this->config['storylines'][$name] ?? throw new MissingConfigurationException("[{$name}] storyline does not exist.");

        $host = $this->getHost();

        $id = $storylineConfig['id'] ?? throw new MissingConfigurationException("[{$name}] storyline ID does not exist.");

        $token = $storylineConfig['token'] ?? throw new MissingConfigurationException("[{$name}] storyline Token does not exist.");

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
}

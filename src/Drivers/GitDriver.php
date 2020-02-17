<?php


namespace Malbrandt\Git\Drivers;


use Malbrandt\Git\Drivers\Error\DriverDoesNotExistException;
use Spatie\Macroable\Macroable;

abstract class GitDriver
{
    use Macroable;

    const HTTP_RESPONSE_OK = 200;
    const HTTP_RESPONSE_MULTIPLE_CHOICE = 300;
    private static $drivers = [];

    /** @var string */
    private $owner;
    /** @var string */
    private $repository;
    /** @var string */
    private $client;
    /** @var array */
    private $options;

    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    public function getOwner(): string
    {
        return $this->owner;
    }

    public function setOwner(string $owner): self
    {
        $this->owner = $owner;
        return $this;
    }

    public function getRepository(): string
    {
        return $this->repository;
    }

    public function setRepository(string $repository): self
    {
        $this->repository = $repository;
        return $this;
    }

    public function getClient(): \GuzzleHttp\Client
    {
        // Simple memoization
        if (! isset($this->client)) {
            $this->client = $this->createClient();
        }

        return $this->client;
    }

    /**
     * @param array $options
     *
     * @return array|\Malbrandt\Git\Data\Commit[]
     */
    abstract public function getCommits(array $options = []): array;

    /**
     * @param array $options
     *
     * @return array
     */
    abstract public function getBranches(array $options = []): array;

    /**
     * @return \GuzzleHttp\Client
     */
    abstract protected function createClient(): \GuzzleHttp\Client;

    public static function register(string $name, string $class)
    {
        static::$drivers[$name] = $class;
    }

    public static function has(string $name): bool
    {
        return array_key_exists($name, static::$drivers);
    }

    /**
     * @param string $name
     *
     * @return \Malbrandt\Git\Drivers\GitDriver
     * @throws \Malbrandt\Git\Drivers\Error\DriverDoesNotExistException
     */
    public static function get(string $name): GitDriver
    {
        // TODO: can implement some factory/container patterns to make it more extensible
        if (! static::has($name)) {
            throw new DriverDoesNotExistException($name);
        }

        return new static::$drivers[$name];
    }

    protected function tryParseResponseBodyAsJson(
        \Psr\Http\Message\ResponseInterface $response
    ) {
        if ($this->isResponseValid($response)) {
            try {
                return json_decode($response->getBody(), true);
            } catch (\Throwable $t) {
                throw new \LogicException('Cannot parse response body as JSON.');
            }
        }

        throw new \LogicException("Invalid response. Status code: [{$response->getStatusCode()}].");
    }

    protected function isResponseValid(
        \Psr\Http\Message\ResponseInterface $response
    ): bool {
        $statusCode = $response->getStatusCode();
        return static::HTTP_RESPONSE_OK <= $statusCode && $statusCode <= static::HTTP_RESPONSE_MULTIPLE_CHOICE;
    }
}

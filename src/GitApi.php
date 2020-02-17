<?php

namespace Malbrandt\Git;

use Malbrandt\Git\Drivers\GitDriver;

/**
 * Git API wrapper.
 *
 * @method array|\Malbrandt\Git\Data\Commit[] getCommits(array $options = [])
 *
 * @package Malbrandt\Git
 * @author  Marek Malbrandt <marek.malbrandt@gmail.com>
 */
class GitApi
{
    /** @var string */
    private $owner;
    /** @var string */
    private $repository;
    /** @var \Malbrandt\Git\Drivers\GitDriver */
    private $driver;

    public function __construct(GitDriver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @return string
     */
    public function getOwner(): string
    {
        return $this->owner;
    }

    /**
     * @param string $owner
     *
     * @return \Malbrandt\Git\GitApi
     */
    public function setOwner(string $owner): self
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * @return string
     */
    public function getRepository(): string
    {
        return $this->repository;
    }

    /**
     * @param string $repository
     */
    public function setRepository(string $repository): self
    {
        $this->repository = $repository;
        return $this;
    }

    /**
     * @return \Malbrandt\Git\Drivers\GitDriver
     */
    public function getDriver(): \Malbrandt\Git\Drivers\GitDriver
    {
        return $this->driver;
    }

    /**
     * @param \Malbrandt\Git\Drivers\GitDriver $driver
     */
    public function setDriver(\Malbrandt\Git\Drivers\GitDriver $driver)
    {
        $this->driver = $driver;
    }

    public function __call($name, $arguments)
    {
        $this->driver->setOwner($this->getOwner());
        $this->driver->setRepository($this->getRepository());

        return $this->driver->{$name}($arguments);
    }
}

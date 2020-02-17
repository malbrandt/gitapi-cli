<?php

namespace Malbrandt\Git\Data;

class Commit
{
    /** @var string */
    private $sha;
    /** @var string */
    private $authorName;
    /** @var string */
    private $message;
    /** @var string */
    private $date;

    public function __construct(
        string $sha,
        string $authorName,
        string $message,
        string $date
    ) {
        $this->sha = $sha;
        $this->authorName = $authorName;
        $this->message = $message;
        $this->date = $date;
    }

    public function getSha(): string
    {
        return $this->sha;
    }

    public function getAuthorName(): string
    {
        return $this->authorName;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getDate(): string
    {
        return $this->date;
    }
}
#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Malbrandt\Git\Console\Commands\RepoGetLatestCommitCommand;
use Malbrandt\Git\Drivers\GitDriver;
use Malbrandt\Git\Drivers\GitHubDriver;
use Symfony\Component\Console\Application;

GitDriver::register('github', GitHubDriver::class);

$app = new Application('GitAPI CLI App', 'v1.0.0');
$app->add(new RepoGetLatestCommitCommand());
$app->run();

<?php


namespace Malbrandt\Git\Drivers;


use GuzzleHttp\Client;
use Malbrandt\Git\Data\Commit;

class GitHubDriver extends GitDriver
{

    /**
     * @param array $options Additional options
     *
     * @return array|\Malbrandt\Git\Data\Commit[]|void
     */
    public function getCommits(array $options = []): array
    {
        $uri = "/repos/{$this->getOwner()}/{$this->getRepository()}/commits";
        $response = $this->getClient()->get($uri);
        $commitsJson = $this->tryParseResponseBodyAsJson($response);
        $commits = [];
        foreach ($commitsJson as $json) {
            $commits[] = new Commit(
                $json['sha'] ?? '(unknown sha)',
                $json['commit']['author']['name'] ?? '(unknown author name)',
                $json['commit']['message'] ?? '(unknown message)',
                $json['commit']['committer']['date'] ?? '(unknown committing date)'
            );
        }

        return $commits;
    }

    /**
     * @param array $options
     */
    public function getBranches(array $options = []): array
    {
        // TODO: Implement listBranches() method.
    }

    /**
     * @inheritDoc
     */
    protected function createClient(): \GuzzleHttp\Client
    {
        return new Client([
            'base_uri' => 'https://api.github.com',
            'timeout'  => 10.0,
            'defaults' => [
                'headers' => [
                    'Accept'       => 'application/vnd.github.v3+json',
                    'Content-Type' => 'application/json',
                ],
            ],
        ]);
    }

}
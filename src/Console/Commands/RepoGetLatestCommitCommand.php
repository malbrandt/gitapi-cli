<?php


namespace Malbrandt\Git\Console\Commands;


use Malbrandt\Git\Data\Commit;
use Malbrandt\Git\Drivers\GitDriver;
use Malbrandt\Git\GitApi;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RepoGetLatestCommitCommand extends Command
{
    protected function configure()
    {
        $this->setName('repo:latest-commit');
        $this->addArgument('repository', InputArgument::REQUIRED,
            'Vendor and repository name, i.e. phpunit/phpunit.');
        $this->addOption('service', 's', InputOption::VALUE_REQUIRED,
            'Service, where to look for repository.', 'github');
        $this->addOption('more', 'm', InputOption::VALUE_NONE,
            'Whether to show more info about latest commit.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $service = $input->getOption('service');
        $driver = GitDriver::get($service);

        $ownerAndRepo = $input->getArgument('repository');
        list($owner, $repo) = $this->parseRepositoryArg($ownerAndRepo);

        $git = new GitApi($driver);
        $git->setOwner($owner)
            ->setRepository($repo);

        $commits = $git->getCommits();
        if (empty($commits)) {
            $output->writeln("No commits found in repository [$ownerAndRepo].");
            return 0;
        }

        $latestCommit = $commits[0];

        $showMore = $input->getOption('more');
        $this->displayCommit($latestCommit, $input, $output, $showMore);

        return 0;
    }

    /**
     * @param $ownerAndRepo
     *
     * @return array
     */
    private function parseRepositoryArg($ownerAndRepo): array
    {
        $containsSlash = preg_match('/\//', $ownerAndRepo);
        if (! $containsSlash) {
            throw new \InvalidArgumentException(
                'Invalid repository name passed.' .
                ' Tip: You need to pass owner and repository name, i.e. "vendor/repo".'
            );
        }

        list($owner, $repo) = explode('/', $ownerAndRepo);

        return [$owner, $repo];
    }

    private function displayCommit(
        Commit $latestCommit,
        InputInterface $input,
        OutputInterface $output,
        bool $showMore = false
    ): void {
        if ($showMore) {
            $output->writeln('SHA: ' . $latestCommit->getSha());
            $output->writeln('Author: ' . $latestCommit->getAuthorName());
            $output->writeln('Message: ' . $latestCommit->getMessage());
            $output->writeln('Date: ' . $latestCommit->getDate());
        } else {
            $output->writeln('SHA: ' . $latestCommit->getSha());
        }
    }
}
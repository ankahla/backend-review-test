<?php

declare(strict_types=1);

namespace App\Command;

use App\Dto\GhEvent;
use App\Repository\ApiEventRepositoryInterface;
use App\Repository\ReadActorRepository;
use App\Repository\ReadEventRepository;
use App\Repository\ReadRepoRepository;
use App\Repository\WriteActorRepository;
use App\Repository\WriteEventRepository;
use App\Repository\WriteRepoRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * This command must import GitHub events.
 * You can add the parameters and code you want in this command to meet the need.
 */
#[AsCommand(
    name: 'app:import-github-events',
    description: 'Import GH events',
)]
class ImportGitHubEventsCommand extends Command
{
    public function __construct(
        private ApiEventRepositoryInterface $apiEventRepository,
        private WriteEventRepository $writeEventRepository,
        private WriteRepoRepository $writeRepoRepository,
        private WriteActorRepository $writeActorRepository,
        private ReadEventRepository $readEventRepository,
        private ReadRepoRepository $readRepoRepository,
        private ReadActorRepository $readActorRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('date', InputArgument::REQUIRED, 'Date of events to import (format Y-m-d-G. Example 2024-09-30-0)')
            ->addOption(
                'limit',
                null,
                InputOption::VALUE_OPTIONAL,
                'Limit number of records to import',
                5000
            )
            ->addUsage('bin/console app:import-github-events 2015-01-15-12')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if (!$date = \DateTime::createFromFormat('Y-m-d-G', $input->getArgument('date'))) {
            $io->error('Date is not valid. Try "Y-m-d-G" format.');

            return Command::FAILURE;
        }

        if ($date > new \DateTime()) {
            $io->error('Date should not be in the future.');

            return Command::FAILURE;
        }

        $ghEvents = $this->apiEventRepository->findAll(
            $date->format('Y'),
            $date->format('m'),
            $date->format('d'),
            $date->format('G'),
        );

        if (null === $ghEvents) {
            $io->error('Unable to read archive');

            return Command::FAILURE;
        }

        $limit = (int) $input->getOption('limit');
        $progressBar = new ProgressBar($output, $limit);
        $progressBar->start();
        $count = 0;

        /** @var GhEvent $ghEvent */
        foreach ($ghEvents as $ghEvent) {
            if (!\in_array($ghEvent->type, GhEvent::TYPES) || $this->readEventRepository->exist((int)$ghEvent->id)) {
                continue;
            }

            if (!$this->readRepoRepository->exist($ghEvent->repo->id)) {
                $this->writeRepoRepository->insert($ghEvent->repo);
            }

            if (!$this->readActorRepository->exist($ghEvent->actor->id)) {
                $this->writeActorRepository->insert($ghEvent->actor);
            }

            $this->writeEventRepository->insert($ghEvent);

            $progressBar->advance();

            if (++$count >= $limit) {
                break;
            }
        }

        $io->success(sprintf('%s events imported', $count));

        return Command::SUCCESS;
    }
}

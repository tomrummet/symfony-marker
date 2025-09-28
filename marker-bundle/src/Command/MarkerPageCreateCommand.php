<?php

namespace Tomrummet\MarkerBundle\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Tomrummet\MarkerBundle\Model\MarkerTypeEnum;
use Tomrummet\MarkerBundle\Repository\ScaffoldRepository;

#[AsCommand(
    name: 'marker:page:create',
    description: 'Create new page structure',
)]
class MarkerPageCreateCommand extends Command
{
    public function __construct(
        public ScaffoldRepository $scaffoldRepository,
        public MarkerTypeEnum $type = MarkerTypeEnum::PAGE,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $name = $io->ask('Page title');
        $slug = $this->scaffoldRepository->getSlugByName($name);
        
        $io->success("With the title {$name} you get the slug {$slug}");

        $confirm = $io->confirm("Do you wish to create the page structure?");
        if (!$confirm) {
            return Command::INVALID;
        }

        if (!$this->scaffoldRepository->createFolder(
            name: $name,
            type: $this->type,
        )) {
            $io->error("Couldn't create the folder {$slug}");

            return Command::FAILURE;
        }

        if (!$this->scaffoldRepository->createFiles(
            name: $name,
            type: $this->type,
        )) {
            $io->error("Couldn't create the file {$name}.md");

            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}

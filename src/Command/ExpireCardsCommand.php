<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Carte;

#[AsCommand(
    name: 'expire-cards',
    description: 'Add a short description for your command',
)]
class ExpireCardsCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }
    protected function configure(): void
    {
        $this
        ->setName('app:expire-cards')
        ->setDescription('Expire cards that have passed their expiration date');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $expiredCards = $this->entityManager->getRepository(Carte::class)->findExpiredCards();

        foreach ($expiredCards as $card) {
            $card->setStatutC('bloquée');
            // You may want to log this action or send a notification to the user
        }

        $this->entityManager->flush();

        $output->writeln('Expired cards updated successfully.');

        return Command::SUCCESS;
    }
}

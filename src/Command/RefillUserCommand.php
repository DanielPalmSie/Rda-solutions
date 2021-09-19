<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RefillUserCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected static $defaultName = 'refill:user';

    /**
     * @var EntityManagerInterface $em
     */
    private $em;

    public function __construct(EntityManagerInterface $em){

        $this->em = $em;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDescription("refill bill (refill bill user by input login).")
            ->setDefinition([
                new InputArgument('login', InputArgument::OPTIONAL, "The login"),
                new InputArgument('amount', InputArgument::OPTIONAL, "The amount"),
            ]);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var User|null $user */
        $user = $this->em->getRepository(User::class)
            ->findOneBy(['username' => $input->getArgument('login')]);

        $user->setBalance($input->getArgument('amount'));

        $this->em->persist($user);
        $this->em->flush();

        $output->writeln("refill user was succeed completed.");
    }

}
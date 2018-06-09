<?php

// copied from https://github.com/symfony/demo

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Utils\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DeleteUserCommand extends Command
{
    protected static $defaultName = 'app:delete-user';

    private $io;
    private $entityManager;
    private $validator;
    private $users;

    public function __construct(EntityManagerInterface $em, Validator $validator, UserRepository $users)
    {
        parent::__construct();

        $this->entityManager = $em;
        $this->validator = $validator;
        $this->users = $users;
    }

    protected function configure()
    {
        $this
            ->setDescription('Deletes users from the database')
            ->addArgument('username', InputArgument::REQUIRED, 'The username of an existing user')
            ->setHelp('delete user by username');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $this->validator->validateUsername($input->getArgument('username'));

        $user = $this->users->findOneByUsername($username);

        if (null === $user) {
            throw new RuntimeException(sprintf('User with username "%s" not found.', $username));
        }

        $userId = $user->getId();

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        $this->io->success(sprintf('User "%s" (ID: %d) was successfully deleted.', $user->getUsername(), $userId ));
    }
}

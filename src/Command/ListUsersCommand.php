<?php

// copied from https://github.com/symfony/demo

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ListUsersCommand extends Command
{
    protected static $defaultName = 'app:list-users';

    private $users;

    public function __construct(UserRepository $users)
    {
        parent::__construct();

        $this->users = $users;
    }

    protected function configure()
    {
        $this
            ->setDescription('Lists all the existing users')
            ->setHelp('list users')
            ->addOption('max-results', null, InputOption::VALUE_OPTIONAL, 'Limits the number of users listed', 50)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $maxResults = $input->getOption('max-results');
        // Use ->findBy() instead of ->findAll() to allow result sorting and limiting
        $allUsers = $this->users->findBy([], ['id' => 'DESC'], $maxResults);

        $usersAsPlainArrays = array_map(function (User $user) {
            return [
                $user->getId(),
                $user->getUsername(),
                implode(', ', $user->getRoles()),
            ];
        }, $allUsers);

        $bufferedOutput = new BufferedOutput();
        $io = new SymfonyStyle($input, $bufferedOutput);
        $io->table(
            ['ID', 'Username', 'Roles'],
            $usersAsPlainArrays
        );

        $usersAsATable = $bufferedOutput->fetch();
        $output->write($usersAsATable);
    }
}

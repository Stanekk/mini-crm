<?php

namespace App\Command;

use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

#[AsCommand(name: 'app:create-admin-user', description: 'Create admin user', help: 'A basic command for create user with admin permissions')]
class CreateAdminCommand extends Command
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = new QuestionHelper();

        $output->writeln([
            '',
            '<info>Admin Creator</info>',
            '<info>==============</info>',
            '',
        ]);

        $emailQuestion = new Question('Email: ');
        $email = $helper->ask($input, $output, $emailQuestion);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $output->writeln('<error>Invalid email</error>');

            return Command::FAILURE;
        }

        $passwordQuestion = new Question('Password: ');
        $passwordQuestion->setHidden(true);
        $password = $helper->ask($input, $output, $passwordQuestion);

        if (strlen($password) < 6) {
            $output->writeln('<error>Passwords is to short</error>');

            return Command::FAILURE;
        }

        $passwordConfirmationQuestion = new Question('Confirm password: ');
        $passwordConfirmationQuestion->setHidden(true);
        $passwordConfirmation = $helper->ask($input, $output, $passwordConfirmationQuestion);

        if ($password !== $passwordConfirmation) {
            $output->writeln('<error>Passwords do not match</error>');

            return Command::FAILURE;
        }

        $userExists = $this->userRepository->findOneBy(['email' => $email]);
        if ($userExists) {
            $output->writeln('<error>This email: '.$email.' is already taken </error>');

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}

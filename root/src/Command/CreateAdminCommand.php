<?php

namespace App\Command;

use App\Dto\User\CreateUserRequestDto;
use App\Enum\Role;
use App\Service\RegisterService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(name: 'app:create-admin-user', description: 'Create admin user', help: 'A basic command for create user with admin permissions')]
class CreateAdminCommand extends Command
{
    private ValidatorInterface $validator;
    private RegisterService $registerService;

    public function __construct(ValidatorInterface $validator, RegisterService $registerService)
    {
        $this->validator = $validator;
        $this->registerService = $registerService;
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

        $passwordQuestion = new Question('Password: ');
        $passwordQuestion->setHidden(true);
        $password = $helper->ask($input, $output, $passwordQuestion);

        $passwordConfirmationQuestion = new Question('Confirm password: ');
        $passwordConfirmationQuestion->setHidden(true);
        $passwordConfirmation = $helper->ask($input, $output, $passwordConfirmationQuestion);

        $createUserRequestDto = new CreateUserRequestDto($email, $password, $passwordConfirmation);
        $errors = $this->validator->validate($createUserRequestDto);

        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $output->writeln('<error>'.$error->getPropertyPath().': '.$error->getMessage().'</error>');
            }

            return Command::FAILURE;
        }

        $admin = $this->registerService->registerUser($createUserRequestDto, Role::Admin);
        $output->writeln([
            '<info>Admin Created!</info>',
            '<info>================</info>',
            '<info>Email: '.$admin->getEmail().'</info>',
        ]);

        return Command::SUCCESS;
    }
}

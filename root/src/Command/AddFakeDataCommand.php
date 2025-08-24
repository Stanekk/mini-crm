<?php

namespace App\Command;

use App\Service\Faker\FakerService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

#[AsCommand(name: 'app:add-fake-data', description: 'Add example data by faker php', help: 'This command adds sample data generated with faker php')]
class AddFakeDataCommand extends Command
{
    private FakerService $faker;

    public function __construct(FakerService $faker)
    {
        $this->faker = $faker;
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = new QuestionHelper();

        $output->writeln([
            '',
            '<info>Fake data creator</info>',
            '<info>==============</info>',
            '',
        ]);

        $numberOfUsersQuestion = new Question('Number of users to generate[Default: 10, Max: 15:');
        $numberOfUser = $helper->ask($input, $output, $numberOfUsersQuestion);

        $generatedUsers = $this->faker->generateFakeUsers($numberOfUser);

        $numberOfCompaniesQuestion = new Question('Number of companies to generate[Default: 10, Max: 20]:');
        $numberOfCompanies = $helper->ask($input, $output, $numberOfCompaniesQuestion);

        $generatedCompanies = $this->faker->generateFakeCompanies($numberOfCompanies);

        $numberOfTasksQuestion = new Question('Number of tasks to generate[Default: 10, Max: 50]:');
        $numberOfTasks = $helper->ask($input, $output, $numberOfTasksQuestion);

        $generatedTasks = $this->faker->generateFakeTasks($numberOfTasks);

        $numberOfClientsQuestion = new Question('Number of clients to generate[Default: 10, Max: 20]:');
        $numberOfClients = $helper->ask($input, $output, $numberOfClientsQuestion);

        $generatedClients = $this->faker->generateFakeClients($numberOfClients);

        return Command::SUCCESS;
    }
}

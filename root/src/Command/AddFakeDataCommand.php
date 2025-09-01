<?php

namespace App\Command;

use App\Service\Faker\FakerService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
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
            '<info>=== Fake Data Creator ===</info>',
            '',
        ]);

        // --- Users ---
        $numberOfUsersQuestion = new Question(
            '<fg=cyan;options=bold>Number of users to generate [Default: 10, Max: 15]:</>',
            10
        );
        $numberOfUser = $helper->ask($input, $output, $numberOfUsersQuestion);

        $output->writeln([
            '<comment>Generating users...</comment>',
        ]);
        $generatedUsers = $this->faker->generateFakeUsers($numberOfUser);
        $output->writeln([
            '<info>Generated users:</info> <fg=green;options=bold>' . count($generatedUsers) . '</>',
            '',
        ]);

        // --- Companies ---
        $numberOfCompaniesQuestion = new Question(
            '<fg=cyan;options=bold>Number of companies to generate [Default: 10, Max: 20]:</>',
            10
        );
        $numberOfCompanies = $helper->ask($input, $output, $numberOfCompaniesQuestion);

        $output->writeln([
            '<comment>Generating companies...</comment>',
        ]);
        $generatedCompanies = $this->faker->generateFakeCompanies($numberOfCompanies);
        $output->writeln([
            '<info>Generated companies:</info> <fg=green;options=bold>' . count($generatedCompanies) . '</>',
            '',
        ]);

        // --- Tasks ---
        $numberOfTasksQuestion = new Question(
            '<fg=cyan;options=bold>Number of tasks to generate [Default: 10, Max: 50]:</>',
            10
        );
        $numberOfTasks = $helper->ask($input, $output, $numberOfTasksQuestion);

        $output->writeln([
            '<comment>Generating tasks...</comment>',
        ]);
        $generatedTasks = $this->faker->generateFakeTasks($numberOfTasks);
        $output->writeln([
            '<info>Generated tasks:</info> <fg=green;options=bold>' . count($generatedTasks) . '</>',
            '',
        ]);

        // --- Clients ---
        $numberOfClientsQuestion = new Question(
            '<fg=cyan;options=bold>Number of clients to generate [Default: 10, Max: 20]:</>',
            10
        );
        $numberOfClients = $helper->ask($input, $output, $numberOfClientsQuestion);

        $output->writeln([
            '<comment>Generating clients...</comment>',
        ]);
        $generatedClients = $this->faker->generateFakeClients($numberOfClients);
        $output->writeln([
            '<info>Generated clients:</info> <fg=green;options=bold>' . count($generatedClients) . '</>',
            '',
        ]);


        //Auto assigning
        $output->writeln([
            '<info>Auto assign mode assigns randomly</info>',
        ]);
        $autoAssigningModeQuestion = new ConfirmationQuestion(
            '<fg=cyan;options=bold>Do you want to enable auto-assignment of records (Task-user, client-company, etc.)? (y/N):</>',
            false
        );

        $autoAssigningMode = $helper->ask($input, $output, $autoAssigningModeQuestion);

        $this->faker->saveGeneratedDataToDataBase($generatedUsers);
        $this->faker->saveGeneratedDataToDataBase($generatedCompanies);

        if ($autoAssigningMode) {
            $generatedClients = $this->faker->assignClientsToCompanies($generatedClients, $generatedCompanies);

            $generatedTasks = $this->faker->assignTasksToUsers($generatedTasks, $generatedUsers);
            $generatedTasks = $this->faker->assignTasksToClients($generatedTasks, $generatedClients);
            $generatedTasks = $this->faker->assignTasksToCompanies($generatedTasks, $generatedCompanies);
        }

        $this->faker->saveGeneratedDataToDataBase($generatedClients);

        $this->faker->saveGeneratedDataToDataBase($generatedTasks);

        $output->writeln('<info>=== Done! ===</info>');

        return Command::SUCCESS;
    }

}

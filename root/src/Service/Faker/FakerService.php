<?php

namespace App\Service\Faker;

use App\Entity\Client;
use App\Entity\Company;
use App\Entity\Task;
use App\Entity\User;
use App\Enum\DataSource;
use App\Enum\TaskStatus;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class FakerService
{
    private Generator $faker;
    private UserPasswordHasherInterface $passwordHasher;
    private EntityManagerInterface $entityManager;

    public function __construct(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager)
    {
        $this->faker = Factory::create('pl_PL');
        $this->passwordHasher = $passwordHasher;
        $this->entityManager = $entityManager;
    }

    public function generateFakeUser(): User
    {
        $user = new User();
        $user->setEmail($this->faker->unique()->safeEmail());
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'password!');
        $user->setPassword($hashedPassword);
        $user->setSource(DataSource::Faker);

        return $user;
    }

    public function generateFakeTask(): Task
    {
        $task = new Task();
        $task->setName($this->faker->sentence(7));
        $task->setDescription($this->faker->text());
        $task->setStatus($this->faker->randomElement(TaskStatus::cases()));
        $randomDate = $this->faker->dateTimeBetween('-2 week', '+4 week');
        $dueDateImmutable = \DateTimeImmutable::createFromMutable($randomDate);
        $task->setDueDate($dueDateImmutable);
        $task->setSource(DataSource::Faker);

        return $task;
    }

    public function generateFakeCompany(): Company
    {
        $company = new Company();
        $company->setName($this->faker->company());
        $company->setEmail($this->faker->unique()->safeEmail());
        $nip = $this->faker->numerify('##########');
        $vat = 'PL'.$nip;
        $company->setNipNumber($nip);
        $company->setVatNumber($vat);
        $company->setSource(DataSource::Faker);

        return $company;
    }

    public function generateFakeClient(): Client
    {
        $client = new Client();
        $client->setEmail($this->faker->unique()->safeEmail());
        $client->setPhone($this->faker->phoneNumber());
        $client->setFirstName($this->faker->firstName());
        $client->setLastName($this->faker->lastName());
        $client->setSource(DataSource::Faker);

        return $client;
    }

    public function generateFakeUsers(int $numberOfUsers = 10): array
    {
        $generatedUsers = [];
        for ($i = 0; $i < $numberOfUsers; ++$i) {
            $user = $this->generateFakeUser();
            $generatedUsers[] = $user;
        }

        return $generatedUsers;
    }

    public function generateFakeTasks(int $numberOfTasks = 10): array
    {
        $generatedTasks = [];
        for ($i = 0; $i < $numberOfTasks; ++$i) {
            $task = $this->generateFakeTask();
            $generatedTasks[] = $task;
        }

        return $generatedTasks;
    }

    public function generateFakeClients(int $numberOfClients = 10): array
    {
        $generatedClients = [];
        for ($i = 0; $i < $numberOfClients; ++$i) {
            $client = $this->generateFakeClient();
            $generatedClients[] = $client;
        }

        return $generatedClients;
    }

    public function generateFakeCompanies(int $numberOfCompanies = 10): array
    {
        $generatedCompanies = [];
        for ($i = 0; $i < $numberOfCompanies; ++$i) {
            $company = $this->generateFakeCompany();
            $generatedCompanies[] = $company;
        }

        return $generatedCompanies;
    }

    public function assignTasksToUsers(array $tasks, array $users): array
    {
        $numberOfTasks = count($tasks);
        $numberOfUsers = count($users);

        if (0 === $numberOfUsers || 0 === $numberOfTasks) {
            return [];
        }

        $unAssignedTasksNumber = rand(0, $numberOfTasks);

        $unAssignedTasks = array_slice($tasks, 0, $unAssignedTasksNumber);

        $restOfTasks = array_slice($tasks, $unAssignedTasksNumber);
        foreach ($restOfTasks as $restOfTask) {
            $randomUser = $users[array_rand($users)];
            $restOfTask->setAssignedTo($randomUser);
        }

        return array_merge($unAssignedTasks, $restOfTasks);
    }

    public function assignClientsToCompanies(array $clients, array $companies): array
    {
        $numberOfCompanies = count($companies);
        $numberOfClients = count($clients);

        if (0 === $numberOfCompanies || 0 === $numberOfClients) {
            return [];
        }

        $unAssignedClientsNumber = rand(0, $numberOfCompanies);
        $unAssignedClients = array_slice($clients, 0, $unAssignedClientsNumber);

        $restOfClients = array_slice($clients, $unAssignedClientsNumber);
        foreach ($restOfClients as $client) {
            $randomCompany = $companies[array_rand($companies)];
            $client->setCompany($randomCompany);
        }

        return array_merge($unAssignedClients, $restOfClients);
    }

    public function assignTasksToClients(array $tasks, array $clients): array
    {
        $numberOfTasks = count($tasks);
        $numberOfClients = count($clients);

        if (0 === $numberOfTasks || 0 === $numberOfClients) {
            return [];
        }

        $unAssignedTasksNumber = rand(0, $numberOfTasks);

        $unAssignedTasks = array_slice($tasks, 0, $unAssignedTasksNumber);

        $restOfTasks = array_slice($tasks, $unAssignedTasksNumber);

        foreach ($restOfTasks as $task) {
            $randomClient = $clients[array_rand($clients)];
            $task->setClient($randomClient);
        }

        return array_merge($unAssignedTasks, $restOfTasks);
    }

    public function assignTasksToCompanies(array $tasks, array $companies): array
    {
        $numberOfTasks = count($tasks);
        $numberOfCompanies = count($companies);

        if (0 === $numberOfTasks || 0 === $numberOfCompanies) {
            return [];
        }

        $unAssignedTasksNumber = rand(0, $numberOfTasks);

        $unAssignedTasks = array_slice($tasks, 0, $unAssignedTasksNumber);

        $restOfTasks = array_slice($tasks, $unAssignedTasksNumber);

        foreach ($restOfTasks as $task) {
            $randomCompany = $companies[array_rand($companies)];
            $task->setCompany($randomCompany);
        }

        return array_merge($unAssignedTasks, $restOfTasks);
    }

    public function saveGeneratedDataToDataBase(array $generatedData): void
    {
        $batchSize = 100;
        foreach ($generatedData as $key => $data) {
            $this->entityManager->persist($data);
            if ((($key + 1) % $batchSize) === 0) {
                $this->entityManager->flush();
            }
        }
        $this->entityManager->flush();
    }
}

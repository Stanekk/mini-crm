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
}

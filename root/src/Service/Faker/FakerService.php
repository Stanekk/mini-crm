<?php

namespace App\Service\Faker;

use App\Entity\Client;
use App\Entity\Company;
use App\Entity\Task;
use App\Entity\User;
use App\Enum\TaskStatus;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class FakerService
{
    private Generator $faker;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->faker = Factory::create('pl_PL');
        $this->passwordHasher = $passwordHasher;
    }

    public function generateFakeUser(): User
    {
        $user = new User();
        $user->setEmail($this->faker->unique()->safeEmail());
        $user->setPassword($this->faker->password());
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'password!');
        $user->setPassword($hashedPassword);

        return $user;
    }

    public function generateFakeTask(): Task
    {
        $task = new Task();
        $task->setName($this->faker->sentence(7));
        $task->setDescription($this->faker->text());
        $task->setStatus($this->faker->randomElement(TaskStatus::cases()));

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

        return $company;
    }

    public function generateFakeClient(): Client
    {
        $client = new Client();
        $client->setEmail($this->faker->unique()->safeEmail());
        $client->setPhone($this->faker->phoneNumber());
        $client->setFirstName($this->faker->firstName());
        $client->setLastName($this->faker->lastName());

        return $client;
    }
}

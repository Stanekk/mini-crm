<?php

namespace App\Tests\Service;

use App\Enum\TaskStatus;
use App\Service\Faker\FakerService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class FakerServiceTest extends TestCase
{
    private FakerService $faker;

    public function setUp(): void
    {
        $hasher = $this->createMock(UserPasswordHasherInterface::class);
        $hasher->method('hashPassword')->willReturn('password!');
        $this->faker = new FakerService($hasher);
    }

    public function testGenerateFakeUser()
    {
        $fakeUser = $this->faker->generateFakeUser();

        $this->assertNotNull($fakeUser->getPassword());
        $this->assertStringContainsString('password!', $fakeUser->getPassword());
        $this->assertStringContainsString('@', $fakeUser->getEmail());
    }

    public function testGenerateFakeCompany()
    {
        $fakeCompany = $this->faker->generateFakeCompany();
        $this->assertNotNull($fakeCompany->getName());
        $this->assertNotNull($fakeCompany->getEmail());
        $this->assertStringContainsString('@', $fakeCompany->getEmail());
        $this->assertEquals(10, strlen($fakeCompany->getNipNumber()));
        $this->assertStringContainsString('PL', $fakeCompany->getVatNumber());
    }

    public function testGenerateFakeTask()
    {
        $fakeTask = $this->faker->generateFakeTask();
        $this->assertNotNull($fakeTask->getName());
        $this->assertContains($fakeTask->getStatus(), TaskStatus::cases());
    }

    public function testGenerateFakeClient()
    {
        $fakeClient = $this->faker->generateFakeClient();
        $this->assertStringContainsString('@', $fakeClient->getEmail());
        $this->assertNotNull($fakeClient->getFirstName());
        $this->assertNotNull($fakeClient->getLastName());
        $this->assertNotNull($fakeClient->getPhone());
    }
}

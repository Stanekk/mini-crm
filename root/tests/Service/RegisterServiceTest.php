<?php

namespace App\Tests\Service;

use App\Dto\User\CreateUserRequestDto;
use App\Entity\User;
use App\Service\RegisterService;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterServiceTest extends TestCase
{
    private $entityManager;
    private $passwordHasher;
    private $registerService;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManager::class);
        $this->passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $this->registerService = new RegisterService($this->entityManager, $this->passwordHasher);
    }

    public function testRegisterUser(): void
    {
        $dto = new CreateUserRequestDto('test@example.com', 'password', 'password');

        $this->passwordHasher->expects($this->once())->method('hashPassword')->with($this->isInstanceOf(User::class), $dto->password)->willReturn('hashedPassword');

        $this->entityManager->expects($this->once())->method('persist')->with($this->isInstanceOf(User::class));

        $this->entityManager->expects($this->once())->method('flush');

        $user = $this->registerService->registerUser($dto);

        $this->assertInstanceOf(User::class, $user);

        $this->assertSame($dto->password, $dto->passwordConfirm);
        $this->assertEquals('test@example.com', $user->getEmail());
        $this->assertEquals('hashedPassword', $user->getPassword());
    }
}

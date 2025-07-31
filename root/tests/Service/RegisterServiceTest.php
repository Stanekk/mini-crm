<?php

namespace App\Tests\Service;

use App\Dto\User\CreateUserRequestDto;
use App\Entity\User;
use App\Service\RegisterService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterServiceTest extends TestCase
{
    private EntityManagerInterface&MockObject $entityManager;
    private UserPasswordHasherInterface&MockObject $passwordHasher;
    private RegisterService $registerService;
    private EventDispatcherInterface&MockObject $eventDispatcher;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManager::class);
        $this->passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->registerService = new RegisterService($this->entityManager, $this->passwordHasher, $this->eventDispatcher);
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

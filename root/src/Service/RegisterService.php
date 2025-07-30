<?php

namespace App\Service;

use App\Dto\User\CreateUserRequestDto;
use App\Entity\User;
use App\Event\UserRegisteredEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterService
{
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $passwordHasher;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher, EventDispatcherInterface $eventDispatcher)
    {
        $this->em = $em;
        $this->passwordHasher = $passwordHasher;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function registerUser(CreateUserRequestDto $dto): ?User
    {
        $user = new User();
        $user->setEmail($dto->email);
        $hashedPassword = $this->passwordHasher->hashPassword($user, $dto->password);
        $user->setPassword($hashedPassword);
        $this->eventDispatcher->dispatch(new UserRegisteredEvent($user));
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}

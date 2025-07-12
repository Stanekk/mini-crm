<?php

namespace App\Service;

use App\Dto\CreateUserRequestDto;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterService
{
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
    {
        $this->em = $em;
        $this->passwordHasher = $passwordHasher;
    }

    public function registerUser(CreateUserRequestDto $dto): ?User
    {
        $user = new User();
        $user->setEmail($dto->email);
        $hashedPassword = $this->passwordHasher->hashPassword($user, $dto->password);
        $user->setPassword($hashedPassword);
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}
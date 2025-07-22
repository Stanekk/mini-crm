<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getUserById(int $id): ?User
    {
        $user = $this->entityManager->getRepository(User::class)->find($id);
        if (!$user) {
            return null;
        }

        return $user;
    }
}

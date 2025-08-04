<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class UserService
{
    private EntityManagerInterface $entityManager;
    private Security $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function getUserById(int $id): ?User
    {
        $user = $this->entityManager->getRepository(User::class)->find($id);
        if (!$user) {
            return null;
        }

        return $user;
    }

    public function deleteUser(User $user): void
    {
        $currentUser = $this->security->getUser();
        if ($currentUser->getId() === $user->getId()) {
            throw new AccessDeniedHttpException('You cannot delete yourself.');
        }
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}

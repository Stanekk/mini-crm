<?php

namespace App\Service;

use App\Entity\Task;
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

        // Set all tasks of the deleted user as unassigned
        $this->entityManager->createQueryBuilder()
            ->update(Task::class, 't')
            ->set('t.assignedTo', ':null')
            ->where('t.assignedTo = :user')
            ->setParameter('null', null)
            ->setParameter('user', $user)
            ->getQuery()
            ->execute();

        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}

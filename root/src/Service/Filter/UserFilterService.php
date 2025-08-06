<?php

namespace App\Service\Filter;

use App\Dto\Filter\UserFilterDto;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

class UserFilterService
{
    public function createFilterFromRequest(Request $request): UserFilterDto
    {
        return new UserFilterDto(
            email: $request->query->get('email'),
        );
    }

    public function applyFilters(QueryBuilder $queryBuilder, UserFilterDto $userFilterDto): void
    {
        if ($userFilterDto->email) {
            $queryBuilder->andWhere('user.email LIKE :email')
                ->setParameter('email', '%'.$userFilterDto->email.'%');
        }
    }
}

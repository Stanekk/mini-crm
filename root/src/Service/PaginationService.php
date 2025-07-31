<?php

namespace App\Service;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PaginationService
{
    private const DEFAULT_LIMIT = 20;

    public function paginate(QueryBuilder $qb, int $page, callable $map): array
    {
        $offset = ($page - 1) * self::DEFAULT_LIMIT;

        $query = $qb
            ->setFirstResult($offset)
            ->setMaxResults(self::DEFAULT_LIMIT)
            ->getQuery();

        $paginator = new Paginator($query, fetchJoinCollection: true);

        $items = array_map($map, iterator_to_array($paginator));

        return [
            'data' => $items,
            'pagination' => [
                'page' => $page,
                'limit' => self::DEFAULT_LIMIT,
                'total' => count($paginator),
                'pages' => ceil(count($paginator) / self::DEFAULT_LIMIT),
            ],
        ];
    }
}

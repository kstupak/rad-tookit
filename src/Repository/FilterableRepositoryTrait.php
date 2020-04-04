<?php

/*
 * This file is part of "rad-tookit".
 *
 * (c) Kostiantyn Stupak <konstantin.stupak@gimmemore.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace KStupak\RAD\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use KStupak\RAD\Model\Filter\DoctrineFilter;
use KStupak\RAD\Model\Search;

/** @property EntityManagerInterface $_em */
/** @property string $_entityClass */
/** @method QueryBuilder createQueryBuilder(string $alias) */
trait FilterableRepositoryTrait
{
    private array $searchableFields = [];
    private array $orderByFields = [];
    private string $entityAlias;

    public function filter(Search $search): Collection
    {
        $query = $this->createQueryBuilder($this->entityAlias);
        $search->getFilters()->map(fn(DoctrineFilter $filter) => $filter->applyTo($query, $this->entityAlias));

        if (!is_null($search->getQuery())) {
            $queries = \array_map(function(string $field) use ($query, $search) {
                return $query->expr()->like(
                    sprintf('%s.%s', $this->entityAlias, $field),
                    sprintf('\'%%%s%%\'', $search->getQuery())
                );
            }, $this->searchableFields);
            $query->andWhere($query->expr()->orX(...$queries));
        }

        foreach ($this->orderByFields as $field => $direction) {
            $query->addOrderBy(sprintf('%s.%s', $this->entityAlias, $field), $direction ?? 'ASC');
        }

        $query->setFirstResult($search->getPagination()->getOffset())
            ->setMaxResults($search->getPagination()->getPageSize());

        $data = $query->getQuery()
            ->getResult();

        $count = $query->select(sprintf('COUNT(%s)', $this->entityAlias))
            ->getQuery()->getSingleScalarResult();

        $search->getPagination()->setTotal((int) $count);

        return new ArrayCollection($data);
    }
}

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

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityNotFoundException;

class GenericRepository extends ServiceEntityRepository
{
    public function get(string $id, ?bool $reference = false): object
    {
        $data = $reference
            ? $this->_em->getReference($this->_entityName, $id)
            : $this->find($id);

        if (empty($data)) {
            throw new EntityNotFoundException(sprintf('Entity not found: %s : %s ', $this->_entityName, $id));
        }

        return $data;
    }

    public function list(): Collection
    {
        $data = $this->findAll();
        return new ArrayCollection($data);
    }

    public function save(object $subject, ?bool $delayed = false): void
    {
        $this->_em->persist($subject);
        if (!$delayed) { $this->_em->flush(); }
    }

    public function remove(object $subject, ?bool $delayed = false): void
    {
        $this->_em->persist($subject);
        if (!$delayed) { $this->_em->flush(); }
    }

    public function flush(): void
    {
        $this->_em->flush();
    }
}

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

namespace KStupak\RAD\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class Search
{
    private ?string $query = null;
    private Collection $filters;
    private Pagination $pagination;

    public function __construct(
        ?string $query,
        ?Collection $filters = null,
        ?Pagination $pagination = null
    ){
        $this->query      = $query;
        $this->filters    = $filters ?? new ArrayCollection();
        $this->pagination = $pagination ?? new Pagination();
    }

    public function getQuery(): ?string
    {
        return $this->query;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function getPagination(): ?Pagination
    {
        return $this->pagination;
    }
}

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

final class Pagination
{
    const DEFAULT_BATCH_SIZE = 50;

    private int $current;
    private int $next;
    private int $previous;
    private int $last;

    private int $pageSize;
    private int $total;

    public function __construct(
        int $total = 0,
        int $current = 1,
        int $pageSize = self::DEFAULT_BATCH_SIZE
    ){
        $this->total    = $total;
        $this->current  = $current;
        $this->pageSize = $pageSize;

        $this->calculate();
    }

    private function calculate(): void
    {
        $this->last = (int) ceil($this->total/$this->pageSize);

        $this->next = $this->last === $this->current ? $this->last : $this->current + 1;
        $this->previous = $this->current === 1 ? 1 : $this->current - 1;
    }

    public function getCurrent(): int
    {
        return $this->current;
    }

    public function getNext(): int
    {
        return $this->next;
    }

    public function getPrevious(): int
    {
        return $this->previous;
    }

    public function getLast(): int
    {
        return $this->last;
    }

    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getFirst(): int
    {
        return 1;
    }

    public function getOffset(): int
    {
        return ($this->current - 1) * $this->pageSize;
    }

    public function setTotal(int $total): void
    {
        $this->total = $total;
        $this->calculate();
    }
}

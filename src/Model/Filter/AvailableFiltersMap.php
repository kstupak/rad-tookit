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

namespace KStupak\RAD\Model\Filter;

interface AvailableFiltersMap
{
    public static function getAvailableFilters(): array;

    public function getFilterClassByName(string $name): string;
    public function getFilterNameByClass(string $class): string;
    public function isAKnownFilter(string $name): bool;
}

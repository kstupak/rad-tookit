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

use Doctrine\ORM\QueryBuilder;

interface DoctrineFilter
{
    public static function createForValue($value, ?bool $invert = false): self;
    public function applyTo(QueryBuilder $builder, string $alias): void;
    public function getValue();
}

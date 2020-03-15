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

/** @property string $columnName */
trait ScalarFilter
{
    /** @var string|int|float */
    private $value;
    private ?bool $inverted;

    private function __construct($value, ?bool $inverted = false)
    {
        $this->value    = $value;
        $this->inverted = $inverted;
    }

    public static function createForValue($value, ?bool $invert = false): DoctrineFilter
    {
        return new self($value, $invert);
    }

    public function applyTo(QueryBuilder $builder, string $alias): void
    {
        $expression = $builder->expr()->eq($this->getColumnName($alias), ':'.$this->getParameterName());
        $expression = $this->inverted
            ? $builder->expr()->not($expression)
            : $expression;

        $builder->andWhere($expression)
            ->setParameter($this->getParameterName(), $this->value);
    }

    public function getValue()
    {
        return $this->value;
    }

    private function getColumnName(string $alias): string
    {
        return sprintf('%s.%s', $alias, $this->columnName);
    }

    private function getParameterName(): string
    {
        return sprintf('%sValue', $this->columnName);
    }
}

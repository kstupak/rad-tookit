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

abstract class RangeFilter implements DoctrineFilter
{
    protected string $columnName;
    protected string $exclude = 'both'; // possible values are: start, end, both, none

    private $rangeStart;
    private $rangeEnd;

    protected function __construct(array $value, ?bool $inverted = false)
    {
        [$this->rangeStart, $this->rangeEnd] = $value;
        $this->inverted = $inverted;
    }

    public static function createForValue($value, ?bool $invert = false): DoctrineFilter
    {
        if (is_string($value) && strpos($value, ',')) {
            $value = explode(',', $value);
        }

        if (!is_array($value)) {
            $value = [$value];
        }

        if (count($value) > 2) {
            $value = \array_slice($value, 0, 2);
        }

        return static::getInstance($value, $invert);
    }

    public function applyTo(QueryBuilder $builder, string $alias): void
    {
        $column = $this->getColumnName($alias);

        $rangeStart = ($this->exclude === 'start' || $this->exclude === 'both')
            ? $builder->expr()->gt($column, ':'.$this->getParameterName('start'))
            : $builder->expr()->gte($column, ':'.$this->getParameterName('start'));

        $rangeEnd = ($this->exclude === 'end' || $this->exclude === 'both')
            ? $builder->expr()->lt($column, ':'.$this->getParameterName('end'))
            : $builder->expr()->lte($column, ':'.$this->getParameterName('end'));

        $builder->andWhere($builder->expr()->andX($rangeStart, $rangeEnd))
            ->setParameter($this->getParameterName('start'), $this->rangeStart)
            ->setParameter($this->getParameterName('end'), $this->rangeEnd);
    }

    public function getValue(): array
    {
        return [$this->rangeStart, $this->rangeEnd];
    }

    private function getColumnName(string $alias): string
    {
        return sprintf('%s.%s', $alias, $this->columnName);
    }

    private function getParameterName(string $position): string
    {
        return sprintf('%s_range%s', $this->columnName, ucwords($position));
    }

    abstract protected static function getInstance($value, $inverted): self;
}

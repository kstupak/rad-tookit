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

/**
 * @property string $columnName
 * @method string getParameterName()
 * @method string getColumnName(string $alias)
 */
trait MultipleValuesFilter
{
    private array $value = [];
    private ?bool $inverted;

    protected function __construct($value, ?bool $inverted = false)
    {
        $this->value    = is_array($value) ? $value : [$value];
        $this->inverted = $inverted;
    }

    public static function createForValue($value, ?bool $invert = false): DoctrineFilter
    {
        return new self($value, $invert);
    }

    public function applyTo(QueryBuilder $builder, string $alias): void
    {
        $expression =
        $expression = $this->inverted
            ? $builder->expr()->notIn($this->getColumnName($alias), ':'.$this->getParameterName())
            : $builder->expr()->in($this->getColumnName($alias), ':'.$this->getParameterName());

        $builder->andWhere($expression)
            ->setParameter($this->getParameterName(), $this->value);
    }

    public function getValue(): array
    {
        return $this->value;
    }
}

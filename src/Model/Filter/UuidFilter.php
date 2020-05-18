<?php

/*
 * This file is part of "rad-tookit".
 *
 * (c) Kostiantyn Stupak <konstantin.stupak@gimmemore.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KStupak\RAD\Model\Filter;

use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @property string $columnName
 * @method string getParameterName()
 * @method string getColumnName(string $alias)
 */
trait UuidFilter
{
    /** @var UuidInterface */
    private $value;
    private ?bool $inverted;

    private function __construct($value, ?bool $inverted = false)
    {
        if (
            !(is_string($value) && Uuid::isValid($value)) &&
            !(is_object($value) && ($value instanceof UuidInterface))
        ) {
            throw new \InvalidArgumentException('Value must be either a valid UUIDv4 string or object implementing UuidInterface');
        }

        $this->value    = is_string($value) ? Uuid::fromString($value) : $value;
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
            ->setParameter($this->getParameterName(), $this->value->getBytes());
    }

    public function getValue(): UuidInterface
    {
        return $this->value;
    }
}
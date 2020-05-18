<?php
/*
 * This file is part of "rad-toolkit".
 *
 * (c) Kostiantyn Stupak <konstantin.stupak@gimmemore.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KStupak\RAD\Model\Filter;

/** @property string $columnName */
trait CommonFilterMethods
{
    private function getColumnName(string $alias): string
    {
        return sprintf('%s.%s', $alias, $this->columnName);
    }

    private function getParameterName(): string
    {
        return sprintf('%sValue', $this->columnName);
    }
}
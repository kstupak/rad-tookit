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

use KStupak\RAD\Exception\FilterException;

trait FilterMap
{
    public function getFilterClassByName(string $name): string
    {
        if (!\array_key_exists($name, static::$map)) {
            throw FilterException::unknownFilter($name);
        }

        return static::$map[$name];
    }

    public function getFilterNameByClass(string $class): string
    {
        if (!\array_key_exists($class, \array_flip(static::$map))) {
            throw FilterException::unknownFilter($class);
        }

        return \array_flip(static::$map)[$class];
    }

    public function isAKnownFilter(string $name): bool
    {
        return \array_key_exists($name, static::$map);
    }
}

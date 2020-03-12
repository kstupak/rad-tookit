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

namespace KStupak\RAD\Exception;

final class FilterException extends \Exception
{
    public static function unknownFilter(string $name): self
    {
        return new self(sprintf('Unknown filter requested: %s', $name));
    }
}

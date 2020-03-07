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

namespace KStupak\RAD\Utils;

use RandomLib\Factory;

final class TokenGenerator
{
    public const DEFAULT_VOCABULARY = '0123456789abcdefghijklmnopqrstuvwxyz';

    public static function generate(int $length, ?string $vocabulary = self::DEFAULT_VOCABULARY): string
    {
        return (new Factory())->getMediumStrengthGenerator()
            ->generateString($length, $vocabulary);
    }
}

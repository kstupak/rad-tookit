<?php
/*
 * This file is part of "rad-toolkit".
 *
 * (c) Kostiantyn Stupak <konstantin.stupak@gimmemore.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KStupak\RAD\Exception\Handler;

final class ExceptionResponseHandlerRegistry
{
    private array $map = [];

    public function register(ExceptionResponseHandler $handler): void
    {
        $this->map[$handler->getSupportedClass()] = $handler;
    }

    public function chooseForThrowable(\Throwable $e): ExceptionResponseHandler
    {
        if (!\array_key_exists(\get_class($e), $this->map)) {
            return $this->map['default'];
        }

        return $this->map[get_class($e)];
    }
}
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

use Symfony\Component\HttpFoundation\Response;

interface ExceptionResponseHandler
{
    public function handle(\Throwable $e): Response;
    public function getSupportedClass(): string;
}
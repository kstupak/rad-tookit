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

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class DefaultHandler implements ExceptionResponseHandler
{
    public function handle(\Throwable $e): Response
    {
        $payload = getenv('APP_ENV') === 'prod'
            ? 'Server error'
            : [
                'file'    => $e->getMessage() . ':' . $e->getLine(),
                'message' => $e->getMessage(),
                'trace'   => $e->getTrace(),
            ];

        return new JsonResponse($payload, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function getSupportedClass(): string
    {
        return 'default';
    }
}
<?php
/*
 * This file is part of "rad-toolkit".
 *
 * (c) Kostiantyn Stupak <konstantin.stupak@gimmemore.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KStupak\RAD\EventSubscriber;

use KStupak\RAD\Exception\Handler\ExceptionResponseHandlerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class ExceptionInterceptor implements EventSubscriberInterface
{
    private LoggerInterface $logger;
    private ExceptionResponseHandlerRegistry $registry;

    public function __construct(LoggerInterface $logger, ExceptionResponseHandlerRegistry $registry)
    {
        $this->logger = $logger;
        $this->registry = $registry;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onException',
        ];
    }

    public function onException(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();
        $this->logException($throwable);

        $response = $this->registry
            ->chooseForThrowable($throwable)
            ->handle($throwable);

        $event->setResponse($response);
        $event->stopPropagation();
    }

    private function logException(\Throwable $e): void
    {
        $format = <<<FORMAT
FILE: %s:%d,
MESSAGE: %s,
TRACE: %s
FORMAT;

        $this->logger->critical(sprintf($format, $e->getFile(), $e->getLine(), $e->getMessage(), $e->getTraceAsString()));
    }
}
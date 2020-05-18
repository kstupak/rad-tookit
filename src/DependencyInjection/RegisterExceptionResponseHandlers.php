<?php
/*
 * This file is part of "rad-toolkit".
 *
 * (c) Kostiantyn Stupak <konstantin.stupak@gimmemore.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KStupak\RAD\DependencyInjection;

use KStupak\RAD\Exception\Handler\ExceptionResponseHandler;
use KStupak\RAD\Exception\Handler\ExceptionResponseHandlerRegistry;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class RegisterExceptionResponseHandlers implements SelfDefiningCompilerPass
{
    private const CONTAINER_TAG = 'exception.handler';

    public function process(ContainerBuilder $container)
    {
        $registryDefinition = $container->getDefinition(ExceptionResponseHandlerRegistry::class);
        $handlers = $container->findTaggedServiceIds(self::CONTAINER_TAG);

        foreach ($handlers as $id => $definition) {
            $registryDefinition->addMethodCall('register', [new Reference($id)]);
        }
    }

    public function register(ContainerBuilder $container): void
    {
        $container->registerForAutoconfiguration(ExceptionResponseHandler::class)
            ->addTag(self::CONTAINER_TAG);

        $container->addCompilerPass($this);
    }
}
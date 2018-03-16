<?php


namespace Moln\ExpressiveCallableCompat;

use Psr\Container\ContainerInterface;

class MiddlewareContainerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new MiddlewareContainer($container);
    }
}

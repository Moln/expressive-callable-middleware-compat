<?php

namespace MolnTest\ExpressiveCallableCompat;

use Moln\ExpressiveCallableCompat\MiddlewareContainer;
use Moln\ExpressiveCallableCompat\MiddlewareContainerFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class MiddlewareContainerFactoryTest extends TestCase
{

    public function testInvoke()
    {
        $factory = new MiddlewareContainerFactory();
        $this->assertInstanceOf(
            MiddlewareContainer::class,
            $factory($this->createMock(ContainerInterface::class))
        );
    }
}

<?php

namespace MolnTest\ExpressiveCallableCompat;

use Moln\ExpressiveCallableCompat\MiddlewareContainer;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Exception;
use Zend\Expressive\Router\Middleware\DispatchMiddleware;
use Zend\Stratigility\Middleware\DoublePassMiddlewareDecorator;
use Zend\Stratigility\Middleware\RequestHandlerMiddleware;

class MiddlewareContainerTest extends TestCase
{
    protected $originContainer;
    protected $container;

    public function setUp()
    {
        $this->originContainer = $this->prophesize(ContainerInterface::class);
        $this->container = new MiddlewareContainer($this->originContainer->reveal());
    }

    public function testGetRaisesExceptionIfServiceIsUnknown()
    {
        $this->originContainer->has('not-a-service')->willReturn(false);
        $this->expectException(Exception\MissingDependencyException::class);
        $this->container->get('not-a-service');
    }
    public function testGetRaisesExceptionIfServiceSpecifiedDoesNotImplementMiddlewareInterface()
    {
        $this->originContainer->has(__CLASS__)->willReturn(true);
        $this->originContainer->get(__CLASS__)->willReturn($this);
        $this->expectException(Exception\InvalidMiddlewareException::class);
        $this->container->get(__CLASS__);
    }
    public function testGetRaisesExceptionIfClassSpecifiedDoesNotImplementMiddlewareInterface()
    {
        $this->originContainer->has(__CLASS__)->willReturn(false);
        $this->originContainer->get(__CLASS__)->shouldNotBeCalled();
        $this->expectException(Exception\InvalidMiddlewareException::class);
        $this->container->get(__CLASS__);
    }
    public function testGetReturnsServiceFromOriginContainer()
    {
        $middleware = $this->prophesize(MiddlewareInterface::class)->reveal();
        $this->originContainer->has('middleware-service')->willReturn(true);
        $this->originContainer->get('middleware-service')->willReturn($middleware);
        $this->assertSame($middleware, $this->container->get('middleware-service'));
    }
    public function testGetReturnsInstantiatedClass()
    {
        $this->originContainer->has(DispatchMiddleware::class)->willReturn(false);
        $this->originContainer->get(DispatchMiddleware::class)->shouldNotBeCalled();
        $middleware = $this->container->get(DispatchMiddleware::class);
        $this->assertInstanceOf(DispatchMiddleware::class, $middleware);
    }
    public function testGetWillDecorateARequestHandlerAsMiddleware()
    {
        $handler = $this->prophesize(RequestHandlerInterface::class)->reveal();
        $this->originContainer->has('AHandlerNotMiddleware')->willReturn(true);
        $this->originContainer->get('AHandlerNotMiddleware')->willReturn($handler);
        $middleware = $this->container->get('AHandlerNotMiddleware');
        // Test that we get back middleware decorating the handler
        $this->assertInstanceOf(RequestHandlerMiddleware::class, $middleware);
        $this->assertAttributeSame($handler, 'handler', $middleware);
    }
    public function testGetReturnsDoublePassMiddlewareDecoratorClass()
    {
        $call = function (ServerRequestInterface $req, ResponseInterface $res, callable $next) {
            return $next($req, $res);
        };

        $this->originContainer->has('double-pass-middleware')->willReturn(true);
        $this->originContainer->get('double-pass-middleware')->willReturn($call);
        $middleware = $this->container->get('double-pass-middleware');
        $this->assertInstanceOf(DoublePassMiddlewareDecorator::class, $middleware);
    }
}

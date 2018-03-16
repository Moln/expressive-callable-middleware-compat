<?php

namespace Moln\ExpressiveCallableCompat;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Expressive\Exception;
use Zend\Expressive\MiddlewareContainer as ZendMiddlewareContainer;
use Zend\Stratigility\Middleware\DoublePassMiddlewareDecorator;
use Zend\Stratigility\Middleware\RequestHandlerMiddleware;

class MiddlewareContainer extends ZendMiddlewareContainer
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var ResponseInterface
     */
    protected $responsePrototype;

    public function __construct(ContainerInterface $container, ResponseInterface $responsePrototype = null)
    {
        parent::__construct($container);
        $this->container = $container;
        $this->responsePrototype = $responsePrototype;
    }

    /**
     * {@inheritdoc}
     */
    public function get($service) : MiddlewareInterface
    {
        if (! $this->has($service)) {
            throw Exception\MissingDependencyException::forMiddlewareService($service);
        }

        $middleware = $this->container->has($service)
            ? $this->container->get($service)
            : new $service();

        if ($middleware instanceof RequestHandlerInterface) {
            $middleware = new RequestHandlerMiddleware($middleware);
        }

        if (is_callable($middleware)) {
            $middleware = new DoublePassMiddlewareDecorator($middleware, $this->responsePrototype);
        }

        if (! $middleware instanceof MiddlewareInterface) {
            throw Exception\InvalidMiddlewareException::forMiddlewareService($service, $middleware);
        }

        return $middleware;
    }
}

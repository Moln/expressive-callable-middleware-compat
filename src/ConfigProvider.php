<?php

namespace Moln\ExpressiveCallableCompat;

use Zend\Expressive\MiddlewareContainer;

class ConfigProvider
{
    public function __invoke()
    {
        return [
            'dependencies' => [
                'factories' => [
                    MiddlewareContainer::class => MiddlewareContainerFactory::class,
                ]
            ],
        ];
    }
}

<?php

namespace Tests\Framework\Module;

use Framework\Router\Router;

class StringModule
{
    public function __construct(Router $router)
    {
        $router->addRoute(
            '/demo',
            function () {
                return 'demo';
            },
            ['GET'],
            'demo'
        );
    }
}
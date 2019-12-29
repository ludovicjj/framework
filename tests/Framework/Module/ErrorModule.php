<?php

namespace Tests\Framework\Module;

use Framework\Router\Router;

class ErrorModule
{
    public function __construct(Router $router)
    {
        $router->addRoute(
            '/demo',
            function () {
                return new \stdClass();
            },
            ['GET'],
            'demo'
        );
    }
}

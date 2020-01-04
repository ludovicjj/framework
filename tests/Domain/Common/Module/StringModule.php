<?php

namespace Tests\Domain\Common\Module;

use App\Domain\Common\Router\Router;

class StringModule
{
    public function __construct(Router $router)
    {
        $router->addRoute(
            ['GET'],
            '/demo',
            function () {
                return 'demo';
            },
            'demo'
        );
    }
}
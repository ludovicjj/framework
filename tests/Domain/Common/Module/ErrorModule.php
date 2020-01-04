<?php

namespace Tests\Domain\Common\Module;

use App\Domain\Common\Router\Router;

class ErrorModule
{
    public function __construct(Router $router)
    {
        $router->addRoute(
            ['GET'],
            '/demo',
            function () {
                return new \stdClass();
            },
            'demo'
        );
    }
}

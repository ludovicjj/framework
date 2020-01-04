<?php

namespace App\Domain\Blog;

use App\Actions\Blog\ListAction;
use App\Actions\Blog\ShowAction;
use App\Domain\Common\Module\Module;
use App\Domain\Common\Router\Interfaces\RouterInterface;

class BlogModule extends Module
{
    const DEFINITIONS = __DIR__ . '/config/config.php';
    const MIGRATIONS =  __DIR__ . '/phinx/migrations';
    const SEEDS =  __DIR__ . '/phinx/seeds';

    /**
     * BlogModule constructor.
     * @param string $prefix
     * @param RouterInterface $router
     */
    public function __construct(
        string $prefix,
        RouterInterface $router
    ) {
        $router->addRoute(
            ['GET'],
            $prefix,
            ListAction::class,
            'blog.index'
        );

        $router->addRoute(
            ['GET'],
            $prefix . '/{slug:[a-z\-0-9]+}-{id:[0-9]+}',
            [ShowAction::class, 'show'],
            'blog.show'
        );
    }
}

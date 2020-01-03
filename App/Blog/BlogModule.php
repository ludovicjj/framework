<?php

namespace App\Blog;

use App\Blog\Actions\BlogListAction;
use App\Blog\Actions\BlogShowAction;
use Framework\Module;
use Framework\Renderer\Interfaces\TwigRendererInterface;
use Framework\Router\Interfaces\RouterInterface;

class BlogModule extends Module
{
    const DEFINITIONS = __DIR__ . '/config/config.php';
    const MIGRATIONS =  __DIR__ . '/phinx/migrations';
    const SEEDS =  __DIR__ . '/phinx/seeds';

    /**
     * BlogModule constructor.
     * @param string $prefix
     * @param RouterInterface $router
     * @param TwigRendererInterface $renderer
     */
    public function __construct(
        string $prefix,
        RouterInterface $router,
        TwigRendererInterface $renderer
    ) {
        $renderer->addPath(__DIR__.'/views', 'blog');

        $router->addRoute(
            ['GET'],
            $prefix,
            BlogListAction::class,
            'blog.index'
        );

        $router->addRoute(
            ['GET'],
            $prefix . '/{slug:[a-z\-0-9]+}-{id:[0-9]+}',
            [BlogShowAction::class, 'show'],
            'blog.show'
        );
    }

    public function test()
    {
        return 'hello';
    }
}

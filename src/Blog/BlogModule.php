<?php

namespace App\Blog;

use App\Blog\Actions\BlogListAction;
use App\Blog\Actions\BlogShowAction;
use Framework\Module;
use Framework\Renderer\RendererInterface;
use Framework\Router\Router;

class BlogModule extends Module
{
    const DEFINITIONS = __DIR__ . '/config/config.php';
    const MIGRATIONS =  __DIR__ . '/phinx/migrations';
    const SEEDS =  __DIR__ . '/phinx/seeds';

    /**
     * BlogModule constructor.
     * @param string $prefix
     * @param Router $router
     * @param RendererInterface $renderer
     */
    public function __construct(
        string $prefix,
        Router $router,
        RendererInterface $renderer
    ) {
        $renderer->addPath(__DIR__.'/views', 'blog');

        $router->addRoute($prefix, BlogListAction::class, ['GET'], 'blog.index');
        $router->addRoute($prefix . '/{slug:[a-z\-0-9]+}', BlogShowAction::class, ['GET'], 'blog.show');
    }
}

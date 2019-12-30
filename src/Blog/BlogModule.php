<?php

namespace App\Blog;

use Framework\Renderer\Renderer;
use Framework\Router\Router;
use Psr\Http\Message\ServerRequestInterface;

class BlogModule
{
    private $renderer;

    public function __construct(
        Router $router,
        Renderer $renderer
    ) {
        $this->renderer = $renderer;
        $this->renderer->addPath(__DIR__.'/views', 'blog');

        $router->addRoute('/blog', [$this, 'index'], ['GET'], 'blog.index');
        $router->addRoute('/blog/{slug:[a-z\-0-9]+}', [$this, 'show'], ['GET'], 'blog.show');
    }

    public function index(ServerRequestInterface $request): string
    {
        return $this->renderer->render('@blog/index.php');
    }

    public function show(ServerRequestInterface $request): string
    {
        return $this->renderer->render(
            '@blog/show.php',
            [
                'slug' => $request->getAttribute('slug')
            ]
        );
    }
}

<?php

namespace App\Blog;

use Framework\Renderer\RendererInterface;
use Framework\Router\Router;
use Psr\Http\Message\ServerRequestInterface;

class BlogModule
{
    /** @var RendererInterface */
    private $renderer;

    public function __construct(
        Router $router,
        RendererInterface $renderer
    ) {
        $this->renderer = $renderer;
        $this->renderer->addPath(__DIR__.'/views', 'blog');

        $router->addRoute('/blog', [$this, 'index'], ['GET'], 'blog.index');
        $router->addRoute('/blog/{slug:[a-z\-0-9]+}', [$this, 'show'], ['GET'], 'blog.show');
    }

    public function index(ServerRequestInterface $request): string
    {
        return $this->renderer->render('@blog/index.html.twig');
    }

    public function show(ServerRequestInterface $request): string
    {
        return $this->renderer->render(
            '@blog/show.html.twig',
            [
                'slug' => $request->getAttribute('slug')
            ]
        );
    }
}

<?php

namespace App\Blog;

use Framework\Router\Router;
use Psr\Http\Message\ServerRequestInterface;

class BlogModule
{
    public function __construct(Router $router)
    {
        $router->addRoute('/blog', [$this, 'index'], ['GET'], 'blog.index');
        $router->addRoute('/blog/{slug:[a-z\-]+}', [$this, 'show'], ['GET'], 'blog.show');
    }

    public function index(ServerRequestInterface $request): string
    {
        return '<h1>Bienvenue sur le blog</h1>';
    }

    public function show(ServerRequestInterface $request): string
    {
        return '<h1>Bienvenue sur la page avec le slug : '.$request->getAttribute('slug').'</h1>';
    }
}

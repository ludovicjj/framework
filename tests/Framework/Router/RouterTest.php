<?php

namespace Tests\Framework\Router;

use Framework\Router\Router;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    /** @var Router */
    private $router;

    public function setUp(): void
    {
        $this->router = new Router();
    }

    public function testGetMethod()
    {
        $request = new ServerRequest('GET', '/blog');
        $this->router->addRoute(
            '/blog',
            function () {
                return 'hello';
            },
            ['GET'],
            'blog'
        );
        $route = $this->router->match($request);

        $this->assertEquals('blog', $route->getName());
        $this->assertEquals('hello', call_user_func_array($route->getCallback(), [$request]));
    }

    public function testInvalidUrl()
    {
        $request = new ServerRequest('GET', '/demo');
        $this->router->addRoute(
            '/blog',
            function () {
                return 'hello';
            },
            ['GET'],
            'blog'
        );
        $route = $this->router->match($request);

        $this->assertNull($route);
    }

    public function testInvalidMethod()
    {
        $request = new ServerRequest('GET', '/demo');
        $this->router->addRoute(
            '/blog',
            function () {
                return 'hello';
            },
            ['POST'],
            'blog'
        );
        $route = $this->router->match($request);

        $this->assertNull($route);
    }

    public function testValidParameters()
    {
        $request = new ServerRequest('GET', '/blog/mon-slug-4');
        $this->router->addRoute(
            '/blog/{slug:[a-z0-9\-]+}-{id:[0-9]+}',
            function () {
                return 'page du post 4';
            },
            ['GET'],
            'posts.show'
        );
        $route = $this->router->match($request);

        $this->assertEquals('posts.show', $route->getName());
        $this->assertEquals('page du post 4', call_user_func_array($route->getCallback(), [$request]));
        $this->assertEquals(
            [
                'slug' => 'mon-slug',
                'id' => 4
            ],
            $route->getParameters()
        );
    }

    public function testInvalidParameters()
    {
        $request = new ServerRequest('GET', '/blog/mon_slug-4');
        $this->router->addRoute(
            '/blog/{slug:[a-z0-9\-]+}-{id:[0-9]+}',
            function () {
                return 'page du post 4';
            },
            ['GET'],
            'posts.show'
        );
        $route = $this->router->match($request);
        $this->assertNull($route);
    }

    public function testGenerateUri()
    {
        $this->router->addRoute(
            '/blog/{slug:[a-z0-9\-]+}-{id:[0-9]+}',
            function () {
                return 'page du post 4';
            },
            ['GET'],
            'posts.show'
        );

        $uri = $this->router->generateUri('posts.show', ['slug' => 'mon-slug', 'id' => 42]);

        $this->assertEquals('/blog/mon-slug-42', $uri);
    }
}

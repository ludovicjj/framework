<?php

namespace Framework;

use Framework\Exception\InvalidResponseException;
use Framework\Router\Router;
use GuzzleHttp\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class App
{
    /** @var string[] */
    private $module;

    /** @var ContainerInterface */
    private $container;

    /**
     * App constructor.
     * @param ContainerInterface $container
     * @param array $modules
     */
    public function __construct(ContainerInterface $container, array $modules = [])
    {
        $this->container = $container;

        foreach ($modules as $module) {
            $this->module = $container->get($module);
        }
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws InvalidResponseException
     */
    public function run(ServerRequestInterface $request): ResponseInterface
    {
        $uri = $request->getUri()->getPath();
        if (!empty($uri) && $uri[-1] === "/") {
            $response = (new Response())
                ->withStatus(301)
                ->withHeader('Location', substr($uri, 0, -1));

            return $response;
        }

        $route = $this->container->get(Router::class)->match($request);

        if (is_null($route)) {
            return new Response(404, [], 'page not found');
        }

        $parameters = $route->getParameters();

        // Add parameters in Request
        $request = array_reduce(
            array_keys($parameters),
            function (ServerRequestInterface $request, $key) use ($parameters) {
                return $request->withAttribute($key, $parameters[$key]);
            },
            $request
        );

        /** @var string|callable $callback */
        $callback = $route->getCallback();

        if (is_string($callback)) {
            $callback = $this->container->get($route->getCallback());
        }

        $response = call_user_func_array(
            $callback,
            [$request]
        );


        if (is_string($response)) {
            return new Response(200, [], $response);
        } elseif ($response instanceof ResponseInterface) {
            return $response;
        } else {
            throw new InvalidResponseException('Response is not a string or instance of ResponseInterface');
        }
    }
}

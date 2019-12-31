<?php

namespace Framework;

use Framework\Exception\InvalidResponseException;
use Framework\Router\Router;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class App
{
    /** @var Router */
    private $router;

    /** @var string[] */
    private $module;

    public function __construct(array $modules = [], array $dependencies = [])
    {
        $this->router = new Router();

        if (array_key_exists('renderer', $dependencies)) {
            $dependencies['renderer']->addGlobal('router', $this->router);
        }

        foreach ($modules as $module) {
            $this->module = new $module($this->router, $dependencies['renderer']);
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

        $route = $this->router->match($request);

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

        $response = call_user_func_array($route->getCallback(), [$request]);


        if (is_string($response)) {
            return new Response(200, [], $response);
        } elseif ($response instanceof ResponseInterface) {
            return $response;
        } else {
            throw new InvalidResponseException('Response is not a string or instance of ResponseInterface');
        }
    }
}

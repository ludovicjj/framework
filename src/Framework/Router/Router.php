<?php

namespace Framework\Router;

use Framework\Middleware\CallableMiddleware;
use Mezzio\Router\FastRouteRouter;
use Mezzio\Router\Route as MezzioRoute;
use Psr\Http\Message\ServerRequestInterface;

class Router
{
    /** @var FastRouteRouter */
    private $router;

    public function __construct()
    {
        $this->router = new FastRouteRouter();
    }

    /**
     * Register new route
     *
     * @param string $path
     * @param callable|string $callback
     * @param array $method
     * @param string $name
     */
    public function addRoute(
        string $path,
        $callback,
        array $method,
        string $name
    ) {
        $this->router->addRoute(new MezzioRoute($path, new CallableMiddleware($callback), $method, $name));
    }

    /**
     * Check if Request match with registered route,
     * Return Route or Null.
     *
     * @param ServerRequestInterface $request
     * @return Route|null
     */
    public function match(ServerRequestInterface $request): ?Route
    {
        $routeResult = $this->router->match($request);

        if ($routeResult->isSuccess()) {
            /** @var CallableMiddleware $callableMiddleware */
            $callableMiddleware = $routeResult->getMatchedRoute()->getMiddleware();

            return new Route(
                $routeResult->getMatchedRouteName(),
                $callableMiddleware->getCallback(),
                $routeResult->getMatchedParams()
            );
        }

        return null;
    }

    /**
     * @param string $name
     * @param array $parameters
     * @return null|string
     */
    public function generateUri(string $name, array $parameters = []): ?string
    {
        return $this->router->generateUri($name, $parameters);
    }
}

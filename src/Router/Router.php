<?php

namespace Framework\Router;

use Framework\Middleware\CallableMiddleware;
use Framework\Router\Interfaces\RouterInterface;
use Mezzio\Router\FastRouteRouter;
use Mezzio\Router\Route as MezzioRoute;
use Psr\Http\Message\ServerRequestInterface;

class Router implements RouterInterface
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
     * @param array $method
     * @param string $path
     * @param array|string|callable $callback
     * @param string $name
     */
    public function addRoute(
        array $method,
        string $path,
        $callback,
        string $name
    ): void {
        $this->router->addRoute(new MezzioRoute($path, new CallableMiddleware($callback), $method, $name));
    }

    /**
     * Get Route from Request
     * if request match with any registered route, return null
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
     * Get Uri from route name
     *
     * @param string $name
     * @param array $parameters
     * @return null|string
     */
    public function generateUri(string $name, array $parameters = []): ?string
    {
        try {
            return $this->router->generateUri($name, $parameters);
        } catch (\Exception $exception) {
            return null;
        }
    }
}

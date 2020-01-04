<?php

namespace App\Domain\Common\Router\Interfaces;

use App\Domain\Common\Router\Route;
use Psr\Http\Message\ServerRequestInterface;

interface RouterInterface
{
    /**
     * Register new route
     *
     * @param array $method
     * @param string $path
     * @param array|string|callable $callback
     * @param string $name
     * @return mixed
     */
    public function addRoute(array $method, string $path, $callback, string $name): void;

    /**
     * Get Route from Request
     * if request match with any registered route, return null
     *
     * @param ServerRequestInterface $request
     * @return Route|null
     */
    public function match(ServerRequestInterface $request): ?Route;

    /**
     * Get Uri from route name
     *
     * @param string $name
     * @param array $parameters
     * @return string|null
     */
    public function generateUri(string $name, array $parameters = []): ?string;
}

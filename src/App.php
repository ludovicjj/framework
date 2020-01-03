<?php

namespace Framework;

use DI\ContainerBuilder;
use Framework\Exception\InvalidResponseException;
use Framework\Router\Interfaces\RouterInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class App
{
    /** @var ContainerInterface */
    private $container;

    /** @var array  */
    private $modules = [];


    public function addModule(string $module): self
    {
        $this->modules[] = $module;
        return $this;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws InvalidResponseException
     */
    public function run(ServerRequestInterface $request): ResponseInterface
    {
        // Load Modules
        foreach ($this->modules as $module) {
            $this->getContainer()->get($module);
        }

        $uri = $request->getUri()->getPath();
        if (!empty($uri) && $uri[-1] === "/") {
            $response = (new Response())
                ->withStatus(301)
                ->withHeader('Location', substr($uri, 0, -1));

            return $response;
        }

        $route = $this->container->get(RouterInterface::class)->match($request);

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

        $callback = $route->getCallback();
        $response = $this->checkCallback($callback, $request);

        if (is_string($response)) {
            return new Response(200, [], $response);
        } elseif ($response instanceof ResponseInterface) {
            return $response;
        } else {
            throw new InvalidResponseException('Response is not a string or instance of ResponseInterface');
        }
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        if ($this->container === null) {
            $builder = new ContainerBuilder();
            $builder->addDefinitions(ROOT . '/config/config.php');

            // add definitions from modules into ContainerBuilder
            foreach ($this->modules as $module) {
                if (!\is_null($module::DEFINITIONS)) {
                    $builder->addDefinitions($module::DEFINITIONS);
                }
            }

            try {
                $this->container = $builder->build();
            } catch (\Exception $exception) {
                echo 'Error container : ',  $exception->getMessage();
            }
        }
        return $this->container;
    }

    /**
     * @param string|array|callable $callback
     * @param ServerRequestInterface $request
     * @return string|ResponseInterface
     */
    private function checkCallback($callback, ServerRequestInterface $request)
    {
        if (is_string($callback)) {
            return call_user_func_array($this->container->get($callback), [$request]);
        }

        if (is_array($callback)) {
            return call_user_func_array([$this->container->get($callback[0]), $callback[1]], [$request]);
        }

        return call_user_func_array($callback, [$request]);
    }
}

<?php

namespace Framework\Twig;

use Framework\Router\Router;
use Twig\Extension\AbstractExtension;
use Twig\Extension\ExtensionInterface;
use Twig\TwigFunction;

class RouterExtension extends AbstractExtension implements ExtensionInterface
{
    /** @var Router */
    private $router;

    /**
     * RouterExtension constructor.
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('path', [$this, 'path']),
        ];
    }

    public function path(string $name, array $parameters = []): string
    {
        return $this->router->generateUri($name, $parameters);
    }
}

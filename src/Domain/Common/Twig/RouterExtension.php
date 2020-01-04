<?php

namespace App\Domain\Common\Twig;

use App\Domain\Common\Router\Interfaces\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\Extension\ExtensionInterface;
use Twig\TwigFunction;

class RouterExtension extends AbstractExtension implements ExtensionInterface
{
    /** @var RouterInterface */
    private $router;

    /**
     * RouterExtension constructor.
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
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

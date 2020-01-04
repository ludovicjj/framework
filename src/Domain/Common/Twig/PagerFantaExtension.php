<?php

namespace App\Domain\Common\Twig;

use App\Domain\Common\Router\Interfaces\RouterInterface;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap4View;
use Twig\Extension\AbstractExtension;
use Twig\Extension\ExtensionInterface;
use Twig\TwigFunction;

class PagerFantaExtension extends AbstractExtension implements ExtensionInterface
{
    /** @var RouterInterface */
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('paginate', [$this, 'paginate'], ['is_safe' => ['html']])
        ];
    }

    public function paginate(Pagerfanta $fantaResult, string $name, array $queryArg = []): string
    {
        $view = new TwitterBootstrap4View();
        $options = [
            'proximity' => 1,
            'prev_message' => '<i class="fas fa-angle-left"></i>',
            'next_message' => '<i class="fas fa-angle-right"></i>'

        ];

        $html = $view->render($fantaResult, function ($page) use ($name, $queryArg) {
            if ($page > 1) {
                return $this->router->generateUri($name, ['page' => $page], $queryArg);
            }
            return $this->router->generateUri($name, [], $queryArg);
        }, $options);

        return $html;
    }
}

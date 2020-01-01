<?php

use Framework\Renderer\RendererInterface;
use Framework\Renderer\TwigRendererFactory;
use Framework\Router\Router;
use Framework\Twig\RouterExtension;
use function DI\factory;
use function DI\create;
use function DI\get;

return [
    'default.path' => dirname(__DIR__).'/views',
    'twig.extensions' => [
        get(RouterExtension::class)
    ],
    Router::class => create(),
    RendererInterface::class => factory(TwigRendererFactory::class),

];

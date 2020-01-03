<?php

use Framework\Renderer\Interfaces\TwigRendererInterface;
use Framework\Renderer\TwigRendererFactory;
use Framework\Router\Interfaces\RouterInterface;
use Framework\Router\Router;
use Framework\Twig\RouterExtension;
use function DI\factory;
use function DI\create;
use function DI\get;
use Psr\Container\ContainerInterface;

return [
    'database.host' => 'localhost',
    'database.username' => 'root',
    'database.password' => '',
    'database.name' => 'jj_framework',

    'default.path' => dirname(__DIR__).'/views',
    'twig.extensions' => [
        get(RouterExtension::class)
    ],
    RouterInterface::class => create(Router::class),
    TwigRendererInterface::class => factory(TwigRendererFactory::class),
    PDO::class => function (ContainerInterface $container) {
        $dsn = 'mysql:dbname='. $container->get('database.name');
        $dsn .= ';host='. $container->get('database.host');
        $dsn .= ';charset=utf8';
        return new PDO(
            $dsn,
            $container->get('database.username'),
            $container->get('database.password'),
            [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );
    }
];

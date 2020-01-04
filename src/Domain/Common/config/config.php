<?php

use App\Domain\Common\Renderer\Interfaces\TwigRendererInterface;
use App\Domain\Common\Renderer\TwigRendererFactory;
use App\Domain\Common\Router\Interfaces\RouterInterface;
use App\Domain\Common\Router\Router;
use App\Domain\Common\Twig\RouterExtension;
use Psr\Container\ContainerInterface;
use function DI\factory;
use function DI\create;
use function DI\get;

return [
    'database.host' => 'localhost',
    'database.username' => 'root',
    'database.password' => '',
    'database.name' => 'jj_framework',
    'default.path' => ROOT.'/views',
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

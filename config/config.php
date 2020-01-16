<?php

use App\Domain\Common\Form\FormFactory;
use App\Domain\Common\Form\Interfaces\FormFactoryInterface;
use App\Domain\Common\Renderer\Interfaces\TwigRendererInterface;
use App\Domain\Common\Renderer\TwigRendererFactory;
use App\Domain\Common\Router\Interfaces\RouterInterface;
use App\Domain\Common\Router\Router;
use App\Domain\Common\Session\Factory\FlashBagFactory;
use App\Domain\Common\Session\Interfaces\FlashBagInterface;
use App\Domain\Common\Session\Interfaces\SessionInterface;
use App\Domain\Common\Session\PHPSession;
use App\Domain\Common\Twig\FlashBagExtension;
use App\Domain\Common\Twig\FormExtension;
use App\Domain\Common\Twig\PagerFantaExtension;
use App\Domain\Common\Twig\RouterExtension;
use App\Domain\Common\Twig\TextExtension;
use App\Domain\Common\Twig\TimeExtension;
use Psr\Container\ContainerInterface;
use function DI\factory;
use function DI\create;
use function DI\get;

return [
    'database.host' => 'localhost',
    'database.username' => 'root',
    'database.password' => '',
    'database.name' => 'jj_framework',
    'default.path' => dirname(__DIR__).'/views',
    'twig.extensions' => [
        get(RouterExtension::class),
        get(PagerFantaExtension::class),
        get(TextExtension::class),
        get(TimeExtension::class),
        get(FlashBagExtension::class),
        get(FormExtension::class)
    ],
    FormFactoryInterface::class => create(FormFactory::class),
    SessionInterface::class => create(PHPSession::class),
    FlashBagInterface::class => factory(FlashBagFactory::class),
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

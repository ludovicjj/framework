<?php

namespace App\Domain\Admin;

use App\Actions\Admin\Posts\CreateAction;
use App\Actions\Admin\Posts\DeleteAction;
use App\Actions\Admin\Posts\EditAction;
use App\Actions\Admin\Posts\IndexAction;
use App\Domain\Common\Module\Module;
use App\Domain\Common\Router\Interfaces\RouterInterface;

class AdminModule extends Module
{
    public const DEFINITIONS = __DIR__ . '/config/config.php';

    public function __construct(
        string $prefix,
        RouterInterface $router
    ) {
        $router->addRoute(
            ['GET'],
            $prefix.'/posts[/page/{page:[0-9]+}]',
            [IndexAction::class, 'index'],
            'admin.posts.index'
        );

        $router->addRoute(
            ['GET', 'POST'],
            $prefix.'/posts/{id:[0-9]+}',
            [EditAction::class, 'edit'],
            'admin.posts.edit'
        );

        $router->addRoute(
            ['GET', 'POST'],
            $prefix.'/posts/new',
            [CreateAction::class, 'create'],
            'admin.posts.create'
        );

        $router->addRoute(
            ['DELETE'],
            $prefix.'/posts/{id:[0-9]+}',
            [DeleteAction::class, 'delete'],
            'admin.posts.delete'
        );
    }
}

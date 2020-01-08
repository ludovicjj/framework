<?php

use App\Domain\Admin\AdminModule;
use function DI\autowire;
use function DI\get;

return [
    'admin.prefix' => '/admin',
    AdminModule::class => autowire()->constructorParameter('prefix', get('admin.prefix'))
];

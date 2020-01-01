<?php

use App\Blog\BlogModule;
use Framework\App;
use Framework\Exception\InvalidResponseException;
use function Http\Response\send;

require '../vendor/autoload.php';

$modules = [
    BlogModule::class
];

$builder = new \DI\ContainerBuilder();
$builder->addDefinitions(dirname(__DIR__).'/config/config.php');

foreach ($modules as $module) {
    if (!\is_null($module::DEFINITIONS)) {
        $builder->addDefinitions($module::DEFINITIONS);
    }
}

try {
    $container = $builder->build();
} catch (\Exception $exception) {
    echo 'Error container : ',  $exception->getMessage();
}

$app = new App($container, $modules);

try {
    $response = $app->run(\GuzzleHttp\Psr7\ServerRequest::fromGlobals());
} catch (InvalidResponseException $responseException) {
    echo 'Error response : ',  $responseException->getMessage();
}
send($response);

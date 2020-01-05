<?php

use App\Domain\Blog\BlogModule;
use App\Domain\Common\App;
use App\Domain\Common\Exception\InvalidResponseException;
use function Http\Response\send;

/**
 * Require autoloader
 */
require dirname(__DIR__).'/vendor/autoload.php';

$modules = [
    BlogModule::class
];

$app = (new App(dirname(__DIR__).'/config/config.php'))
    ->addModule(BlogModule::class);

if (php_sapi_name() !== 'cli') {
    try {
        $response = $app->run(\GuzzleHttp\Psr7\ServerRequest::fromGlobals());
    } catch (InvalidResponseException $responseException) {
        echo 'Error response : ',  $responseException->getMessage();
    }
    send($response);
}

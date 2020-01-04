<?php

use App\Domain\Blog\BlogModule;
use App\Domain\Common\App;
use App\Domain\Common\Exception\InvalidResponseException;
use function Http\Response\send;

/**
 * Define the root directory
 */
define('ROOT', realpath(dirname(__DIR__)));

/**
 * Require autoloader
 */
require ROOT.'/vendor/autoload.php';

$modules = [
    BlogModule::class
];

$app = (new App())->addModule(BlogModule::class);

if (php_sapi_name() !== 'cli') {
    try {
        $response = $app->run(\GuzzleHttp\Psr7\ServerRequest::fromGlobals());
    } catch (InvalidResponseException $responseException) {
        echo 'Error response : ',  $responseException->getMessage();
    }
    send($response);
}

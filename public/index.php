<?php

use App\Blog\BlogModule;
use function Http\Response\send;

require '../vendor/autoload.php';

$app = new \Framework\App([
    BlogModule::class
]);

$response = $app->run(\GuzzleHttp\Psr7\ServerRequest::fromGlobals());
send($response);

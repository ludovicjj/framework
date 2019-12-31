<?php

use App\Blog\BlogModule;
use function Http\Response\send;

require '../vendor/autoload.php';

$renderer = new \Framework\Renderer\TwigRenderer(dirname(__DIR__).'/views');

$app = new \Framework\App(
    [
        BlogModule::class
    ],
    [
        'renderer' => $renderer
    ]
);

$response = $app->run(\GuzzleHttp\Psr7\ServerRequest::fromGlobals());
send($response);

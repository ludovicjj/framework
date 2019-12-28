<?php

namespace tests\Framework;

use Framework\App;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{
    public function testRedirectTrailingSlash()
    {
        $request = new ServerRequest('GET', '/demo/');
        $app = new App();
        $response = $app->run($request);

        $this->assertContains('/demo', $response->getHeader('Location'));
        $this->assertEquals(301, $response->getStatusCode());
    }

    public function testResponseBody()
    {
        $request = new ServerRequest('GET', '/blog');
        $app = new App();
        $response = $app->run($request);

        $this->assertEquals('page du blog', $response->getBody()->__toString());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testError404()
    {
        $request = new ServerRequest('GET', '/azerty');
        $app = new App();
        $response = $app->run($request);

        $this->assertEquals('page not found', $response->getBody()->__toString());
        $this->assertEquals(404, $response->getStatusCode());
    }
}
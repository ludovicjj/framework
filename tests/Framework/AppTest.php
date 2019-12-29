<?php

namespace Tests\Framework;

use App\Blog\BlogModule;
use Framework\App;
use Framework\Exception\InvalidResponseException;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Tests\Framework\Module\ErrorModule;
use Tests\Framework\Module\StringModule;

class AppTest extends TestCase
{
    /**
     * @throws InvalidResponseException
     */
    public function testRedirectTrailingSlash()
    {
        $request = new ServerRequest('GET', '/demo/');
        $app = new App();
        $response = $app->run($request);

        $this->assertContains('/demo', $response->getHeader('Location'));
        $this->assertEquals(301, $response->getStatusCode());
    }

    /**
     * @throws InvalidResponseException
     */
    public function testBlog()
    {
        $requestIndex = new ServerRequest('GET', '/blog');
        $requestShow = new ServerRequest('GET', '/blog/mon-article');

        $app = new App([
            BlogModule::class
        ]);

        $responseIndex = $app->run($requestIndex);
        $responseShow = $app->run($requestShow);

        $this->assertEquals(
            '<h1>Bienvenue sur le blog</h1>',
            $responseIndex->getBody()->__toString()
        );
        $this->assertEquals(200, $responseIndex->getStatusCode());

        $this->assertEquals(
            '<h1>Bienvenue sur la page avec le slug : mon-article</h1>',
            $responseShow->getBody()->__toString()
        );
        $this->assertEquals(200, $responseShow->getStatusCode());
    }

    /**
     * @throws InvalidResponseException
     */
    public function testError404()
    {
        $request = new ServerRequest('GET', '/azerty');
        $app = new App();
        $response = $app->run($request);

        $this->assertEquals('page not found', $response->getBody()->__toString());
        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * @throws InvalidResponseException
     */
    public function testException()
    {
        $request = new ServerRequest('GET', '/demo');
        $app = new App([ErrorModule::class]);
        $this->expectException(InvalidResponseException::class);
        $app->run($request);
    }

    /**
     * @throws InvalidResponseException
     */
    public function testConvertStringToResponse()
    {
        $request = new ServerRequest('GET', '/demo');
        $app = new App([StringModule::class]);
        $response = $app->run($request);
        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals('demo', $response->getBody()->__toString());
    }
}

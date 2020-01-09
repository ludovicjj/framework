<?php

namespace Tests\Domain\Admin\Handler\Posts;

use App\Domain\Admin\Handler\Posts\CreateHandler;
use App\Domain\Blog\Entity\PostEntity;
use App\Domain\Blog\Repository\PostRepository;
use GuzzleHttp\Psr7\ServerRequest;
use Tests\DatabaseTestCase;

class CreateHandlerTest extends DatabaseTestCase
{
    /** @var CreateHandler */
    private $handler;

    /** @var PostRepository */
    private $postRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->postRepository = new PostRepository($this->pdo);
        $this->handler = new CreateHandler($this->postRepository);
    }

    public function testHandleWithMethodGet()
    {
        $request = $this->makeRequest('GET', '/');
        $result = $this->handler->handle($request);
        $this->assertFalse($result);
    }

    public function testHandleWithMethodPost()
    {
        $request = $this->makeRequest('POST', '/');
        $request = $request->withParsedBody(
            [
                'name' => 'demo',
                'slug' => 'demo-test',
                'content' => 'my awesome content'
            ]
        );
        $result = $this->handler->handle($request);
        $this->assertTrue($result);
    }

    public function testInsertInCreateHandler()
    {
        $request = $this->makeRequest('POST', '/');
        $request = $request->withParsedBody(
            [
                'name' => 'demo',
                'slug' => 'demo-test',
                'content' => 'my awesome content'
            ]
        );
        $this->handler->handle($request);

        $post = $this->postRepository->find(1);
        $this->assertInstanceOf(PostEntity::class, $post);
        $this->assertEquals('demo', $post->name);
        $this->assertEquals('demo-test', $post->slug);
        $this->assertEquals('my awesome content', $post->content);
    }

    /**
     * @throws \ReflectionException
     */
    public function testFilterParams()
    {
        $request = $this->makeRequest('POST', '/');
        $request = $request->withParsedBody(
            [
                'name' => 'demo',
                'slug' => 'demo-test',
                'content' => 'my awesome content',
                'leviathan' => 'alt 236'
            ]
        );
        $filterParams = self::callPrivateMethod($this->handler, 'getFilterParams', [$request]);
        $this->assertIsArray($filterParams);
        $this->assertCount(3, $filterParams);
        $this->assertSame(
            ['name' => 'demo', 'slug' => 'demo-test', 'content' => 'my awesome content'],
            $filterParams
        );
    }

    /**
     * Static method to test private or protected method
     * By using reflexion to set them to be public.
     *
     * @param $obj
     * @param string $name
     * @param array $args
     * @return mixed
     * @throws \ReflectionException
     */
    public static function callPrivateMethod($obj, string $name, array $args)
    {
        $class = new \ReflectionClass($obj);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method->invokeArgs($obj, $args);
    }

    /**
     * Build new ServerRequest
     *
     * @param string $method
     * @param string $path
     * @return ServerRequest
     */
    private function makeRequest(string $method, string $path)
    {
        return new ServerRequest($method, $path);
    }
}

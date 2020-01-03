<?php

namespace Tests\App\Blog\Actions;

use App\Blog\Actions\BlogShowAction;
use App\Blog\Repository\PostRepository;
use Framework\Exception\NotFoundRecordsException;
use Framework\Renderer\Interfaces\TwigRendererInterface;
use Framework\Router\Interfaces\RouterInterface;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

class BlogShowActionTest extends TestCase
{
    /** @var BlogShowAction */
    private $action;

    private $twig;

    private $postRepository;

    private $router;

    public function setUp(): void
    {
        $this->twig = $this->prophesize(TwigRendererInterface::class);
        $this->postRepository = $this->prophesize(PostRepository::class);
        $this->router = $this->prophesize(RouterInterface::class);

        $this->action = new BlogShowAction(
            $this->twig->reveal(),
            $this->postRepository->reveal(),
            $this->router->reveal()
        );
    }

    public function makePost(int $id, string $slug): \stdClass
    {
        $post = new \stdClass();
        $post->id = $id;
        $post->slug = $slug;
        return $post;
    }

    /**
     * Test RedirectResponse with invalid slug
     */
    public function testShowRedirect()
    {
        $post = $this->makePost(9, "demo-test");
        $request = (new ServerRequest('GET', '/'))
            ->withAttribute('id', $post->id)
            ->withAttribute('slug', 'demo')
        ;

        $this->router->generateUri(
            'blog.show',
            [
                'slug' => $post->slug,
                'id' => $post->id
            ]
        )->willReturn('/blog/demo-test-9');

        $this->postRepository->find($post->id)->willReturn($post);

        $response = call_user_func_array([$this->action, 'show'], [$request]);
        $this->assertEquals(301, $response->getStatusCode());
        $this->assertEquals(['/blog/demo-test-9'], $response->getHeader('Location'));
    }

    public function testShowRender()
    {
        $post = $this->makePost(9, "demo-test");
        $request = (new ServerRequest('GET', '/'))
            ->withAttribute('id', $post->id)
            ->withAttribute('slug', 'demo-test')
        ;

        $this->postRepository->find($post->id)->willReturn($post);
        $this->twig->render('@blog/show.html.twig', ['post' => $post])->willReturn('my test response');

        $response = call_user_func_array([$this->action, 'show'], [$request]);
        $this->assertEquals('my test response', $response);
    }

    public function testShowException()
    {
        $post = $this->makePost(9, "demo-test");
        $request = (new ServerRequest('GET', '/'))
            ->withAttribute('id', $post->id)
            ->withAttribute('slug', 'demo-test')
        ;

        $this->postRepository->find($post->id)->willReturn(false);

        $this->expectException(NotFoundRecordsException::class);
        call_user_func_array([$this->action, 'show'], [$request]);
    }
}

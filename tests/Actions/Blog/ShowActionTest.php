<?php

namespace Tests\Actions\Blog;

use App\Actions\Blog\ShowAction;
use App\Domain\Blog\Entity\PostEntity;
use App\Domain\Blog\Repository\PostRepository;
use App\Domain\Common\Exception\NotFoundRecordsException;
use App\Domain\Common\Renderer\Interfaces\TwigRendererInterface;
use App\Domain\Common\Router\Interfaces\RouterInterface;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

class ShowActionTest extends TestCase
{
    /** @var ShowAction */
    private $action;

    private $twig;

    private $postRepository;

    private $router;

    public function setUp(): void
    {
        $this->twig = $this->prophesize(TwigRendererInterface::class);
        $this->postRepository = $this->prophesize(PostRepository::class);
        $this->router = $this->prophesize(RouterInterface::class);

        $this->action = new ShowAction(
            $this->twig->reveal(),
            $this->postRepository->reveal(),
            $this->router->reveal()
        );
    }

    /**
     * @param int $id
     * @param string $slug
     * @return PostEntity
     * @throws \Exception
     */
    public function makePost(int $id, string $slug): PostEntity
    {
        $post = new PostEntity();
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
        $this->twig->render('/blog/show.html.twig', ['post' => $post])->willReturn('my test response');

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

        $this->postRepository->find($post->id)->willReturn(null);

        $this->expectException(NotFoundRecordsException::class);
        call_user_func_array([$this->action, 'show'], [$request]);
    }
}

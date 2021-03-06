<?php

namespace Tests\Actions\Blog;

use App\Actions\Blog\ShowAction;
use App\Domain\Entity\PostEntity;
use App\Domain\Repository\PostRepository;
use App\Domain\Common\Exception\NotFoundRecordsException;
use App\Domain\Common\Renderer\Interfaces\TwigRendererInterface;
use App\Domain\Common\Router\Interfaces\RouterInterface;
use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;

class ShowActionTest extends TestCase
{
    /** @var ShowAction */
    private $action;

    private $renderer;

    private $postRepository;

    private $router;

    public function setUp(): void
    {
        $this->renderer = $this->prophesize(TwigRendererInterface::class);
        $this->postRepository = $this->prophesize(PostRepository::class);
        $this->router = $this->prophesize(RouterInterface::class);

        $this->action = new ShowAction(
            $this->renderer->reveal(),
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
        $post->setId($id);
        $post->setSlug($slug);
        return $post;
    }

    /**
     * Test RedirectResponse with invalid slug
     *
     * @throws \Exception
     */
    public function testShowRedirect()
    {
        $post = $this->makePost(9, "demo-test");
        $request = (new ServerRequest('GET', '/'))
            ->withAttribute('id', $post->getId())
            ->withAttribute('slug', 'demo')
        ;

        $this->router->generateUri(
            'blog.show',
            [
                'slug' => $post->getSlug(),
                'id' => $post->getId()
            ]
        )->willReturn('blog/demo-test-9');

        $this->postRepository->find($post->getId())->willReturn($post);

        $response = call_user_func_array([$this->action, 'show'], [$request]);
        $this->assertEquals(301, $response->getStatusCode());
        $this->assertEquals(['blog/demo-test-9'], $response->getHeader('Location'));
    }

    public function testShowRender()
    {
        $post = $this->makePost(9, "demo-test");
        $request = (new ServerRequest('GET', '/'))
            ->withAttribute('id', $post->getId())
            ->withAttribute('slug', 'demo-test')
        ;

        $this->postRepository->find($post->getId())->willReturn($post);
        $this->renderer->render('blog/show.html.twig', ['post' => $post])->willReturn('my test response');

        $response = call_user_func_array([$this->action, 'show'], [$request]);
        $this->assertEquals('my test response', $response);
    }

    public function testShowException()
    {
        $post = $this->makePost(9, "demo-test");
        $request = (new ServerRequest('GET', '/'))
            ->withAttribute('id', $post->getId())
            ->withAttribute('slug', 'demo-test')
        ;

        $this->postRepository->find($post->getId())->willReturn(null);

        $this->expectException(NotFoundRecordsException::class);
        call_user_func_array([$this->action, 'show'], [$request]);
    }
}

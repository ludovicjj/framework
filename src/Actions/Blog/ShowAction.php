<?php

namespace App\Actions\Blog;

use App\Domain\Repository\PostRepository;
use App\Domain\Common\Exception\NotFoundRecordsException;
use App\Domain\Common\Renderer\Interfaces\TwigRendererInterface;
use App\Domain\Common\Router\Interfaces\RouterInterface;
use App\Domain\Common\Router\Utils\RouterAwareAction;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ShowAction
{
    /** @var TwigRendererInterface */
    private $renderer;

    /** @var PostRepository */
    private $postRepository;

    /** @var RouterInterface */
    private $router;

    use RouterAwareAction;

    /**
     * BlogShowAction constructor.
     * @param TwigRendererInterface $renderer
     * @param PostRepository $postRepository
     * @param RouterInterface $router
     */
    public function __construct(
        TwigRendererInterface $renderer,
        PostRepository $postRepository,
        RouterInterface $router
    ) {
        $this->renderer = $renderer;
        $this->postRepository = $postRepository;
        $this->router = $router;
    }

    /**
     * Show a single post
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface|string
     * @throws NotFoundRecordsException
     */
    public function show(ServerRequestInterface $request)
    {
        $slug = $request->getAttribute('slug');

        $post = $this->postRepository->find((int)$request->getAttribute('id'));

        if (\is_null($post)) {
            throw new NotFoundRecordsException();
        }

        if ($post->getSlug() !== $slug) {
            return $this->redirect('blog.show', ['slug' => $post->getSlug(), 'id' => $post->getId()]);
        }

        return $this->renderer->render('blog/show.html.twig', ['post' => $post]);
    }
}

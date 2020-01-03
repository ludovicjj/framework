<?php

namespace App\Blog\Actions;

use App\Blog\Repository\PostRepository;
use Framework\Exception\NotFoundRecordsException;
use Framework\Renderer\Interfaces\TwigRendererInterface;
use Framework\Router\Interfaces\RouterInterface;
use Framework\Router\Utils\RouterAwareAction;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class BlogShowAction
{
    /** @var TwigRendererInterface */
    private $twig;

    /** @var PostRepository */
    private $postRepository;

    /** @var RouterInterface */
    private $router;

    use RouterAwareAction;

    /**
     * BlogShowAction constructor.
     * @param TwigRendererInterface $twig
     * @param PostRepository $postRepository
     * @param RouterInterface $router
     */
    public function __construct(
        TwigRendererInterface $twig,
        PostRepository $postRepository,
        RouterInterface $router
    ) {
        $this->twig = $twig;
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

        $post = $this->postRepository->find($request->getAttribute('id'));

        if ($post === false) {
            throw new NotFoundRecordsException();
        }

        if ($post->slug !== $slug) {
            return $this->redirect('blog.show', ['slug' => $post->slug, 'id' => $post->id]);
        }

        return $this->twig->render('@blog/show.html.twig', ['post' => $post]);
    }
}

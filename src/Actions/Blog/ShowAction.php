<?php

namespace App\Actions\Blog;

use App\Domain\Blog\Repository\PostRepository;
use App\Domain\Common\Exception\NotFoundRecordsException;
use App\Domain\Common\Renderer\Interfaces\TwigRendererInterface;
use App\Domain\Common\Router\Interfaces\RouterInterface;
use App\Domain\Common\Router\Utils\RouterAwareAction;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ShowAction
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

        return $this->twig->render('/blog/show.html.twig', ['post' => $post]);
    }
}

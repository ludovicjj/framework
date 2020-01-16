<?php

namespace App\Actions\Admin\Posts;

use App\Domain\Admin\Form\AddPostType;
use App\Domain\Admin\Handler\Posts\CreateHandler;
use App\Domain\Common\Form\Interfaces\FormFactoryInterface;
use App\Domain\Common\Renderer\Interfaces\TwigRendererInterface;
use App\Domain\Common\Router\Interfaces\RouterInterface;
use App\Responder\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CreateAction
{
    /** @var TwigRendererInterface */
    private $renderer;

    /** @var RouterInterface */
    private $router;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var CreateHandler */
    private $handler;

    /**
     * CreateAction constructor.
     * @param TwigRendererInterface $renderer
     * @param RouterInterface $router
     * @param FormFactoryInterface $formFactory
     * @param CreateHandler $handler
     */
    public function __construct(
        TwigRendererInterface $renderer,
        RouterInterface $router,
        FormFactoryInterface $formFactory,
        CreateHandler $handler
    ) {
        $this->renderer = $renderer;
        $this->router = $router;
        $this->formFactory = $formFactory;
        $this->handler = $handler;
    }

    /**
     * Create Post action
     *
     * @param ServerRequestInterface $request
     * @return string|ResponseInterface
     */
    public function create(ServerRequestInterface $request)
    {
        $form = $this->formFactory->create(AddPostType::class)->handleRequest($request);
        if ($this->handler->handle($form)) {
            return new RedirectResponse($this->router->generateUri('admin.posts.index'));
        }
        /*if ($this->handler->handle($request) === true) {
            return new RedirectResponse($this->router->generateUri('admin.posts.index'));
        }*/
        return $this->renderer->render(
            'admin/posts/create.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }
}

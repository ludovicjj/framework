<?php

namespace App\Domain\Admin\Handler\Posts;

use App\Domain\Common\Form\Interfaces\FormInterface;
use App\Domain\Repository\PostRepository;
use App\Domain\Common\Session\Interfaces\FlashBagInterface;

class CreateHandler extends AbstractHandler
{
    /** @var PostRepository */
    private $postRepository;

    /** @var FlashBagInterface */
    private $flashBag;

    /**
     * CreateHandler constructor.
     * @param PostRepository $postRepository
     * @param FlashBagInterface $flashBag
     */
    public function __construct(
        PostRepository $postRepository,
        FlashBagInterface $flashBag
    ) {
        $this->postRepository = $postRepository;
        $this->flashBag = $flashBag;
    }

    /**
     * Check method from request,
     * filter request body by method getFilterParams() from abstract class AbstractHandler.
     * Save post in DB.
     *
     * @param FormInterface $form
     * @return bool
     */
    public function handle(FormInterface $form)
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $this->getFilterParams($form->getData());
            $data = array_merge(
                $data,
                [
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]
            );
            $this->postRepository->insert($data);
            $this->flashBag->add('success', 'L\'article a bien été ajouté');
            return true;
        }
        return false;
    }
}

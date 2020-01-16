<?php

namespace App\Domain\Admin\Handler\Posts;

use App\Domain\Common\Form\Interfaces\FormInterface;
use App\Domain\Entity\PostEntity;
use App\Domain\Repository\PostRepository;
use App\Domain\Common\Session\Interfaces\FlashBagInterface;

class EditHandler extends AbstractHandler
{
    /** @var PostRepository */
    private $postRepository;

    /** @var FlashBagInterface */
    private $flashBag;

    /**
     * EditHandler constructor.
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
     * @param FormInterface $form
     * @param PostEntity $post
     * @return bool
     */
    public function handle(FormInterface $form, PostEntity $post): bool
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $this->getFilterParams($form->getData());
            $data['updated_at'] = date('Y-m-d H:i:s');

            $this->postRepository->update(
                (int)$post->getId(),
                $data
            );

            $this->flashBag->add('success', 'L\'article a bien été modifié');

            return true;
        }
        return false;
    }
}

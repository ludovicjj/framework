<?php

namespace App\Domain\Common\Form;

use App\Domain\Common\Form\Interfaces\FormFactoryInterface;
use App\Domain\Common\Form\Interfaces\FormInterface;

class FormFactory implements FormFactoryInterface
{
    public function create(string $type, ?object $entity = null): FormInterface
    {
        $form = new Form($type, $entity);
        return $form;
    }
}

<?php

namespace App\Domain\Common\Form\Interfaces;

interface FormFactoryInterface
{
    /**
     * Create new FormInterface
     *
     * @param string $type
     * @param object|null $entity
     * @return FormInterface
     */
    public function create(string $type, ?object $entity = null): FormInterface;
}

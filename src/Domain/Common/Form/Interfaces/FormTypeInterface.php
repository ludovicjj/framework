<?php

namespace App\Domain\Common\Form\Interfaces;

use App\Domain\Common\Form\FormBuilder;
use App\Domain\Common\Validator\Validator;

interface FormTypeInterface
{
    /**
     * Builds the form.
     *
     * @param FormBuilder $formBuilder
     */
    public function buildForm(FormBuilder $formBuilder);

    /**
     * Build validator
     *
     * @param Validator $validator
     */
    public function buildValidator(Validator $validator);
}

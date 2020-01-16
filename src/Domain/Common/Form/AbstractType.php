<?php

namespace App\Domain\Common\Form;

use App\Domain\Common\Form\Interfaces\FormTypeInterface;
use App\Domain\Common\Validator\Validator;

abstract class AbstractType implements FormTypeInterface
{
    public function buildForm(FormBuilder $formBuilder)
    {
    }

    public function buildValidator(Validator $validator)
    {
    }
}

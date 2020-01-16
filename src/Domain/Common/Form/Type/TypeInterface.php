<?php

namespace App\Domain\Common\Form\Type;

interface TypeInterface
{
    /**
     * Build HTML field
     *
     * @param array $errors
     * @param string $key
     * @param $value
     * @param array $options
     * @return string
     */
    public function createField(array $errors, string $key, $value, array $options): string;
}

<?php

namespace App\Domain\Common\Form\Type;

class TextareaType extends AbstractType
{
    /**
     * @param array $errors
     * @param string $key
     * @param $value
     * @param array $options
     * @return string
     */
    public function createField(array $errors, string $key, $value, array $options): string
    {
        $error = $this->getErrorHtml($errors, $key);

        $attributes = [
            'class' => 'form-control',
            'id' => $key,
            'name' => $key,
            'rows' => $options['attr']['rows'] ?? '4',
            'placeholder' => $options['attr']['placeholder'] ?? false
        ];

        //TODO Update attributes class if error
        if ($error) {
            $attributes['class'] .= ' is-invalid';
        }

        $input = $this->getField($value, $attributes);
        $label = $this->getLabel($key, $options['label'] ?? null);

        return "<div class=\"form-group\">{$label}{$input}{$error}</div>";
    }

    /**
     * Make field <textarea>
     *
     * @param string|null $value
     * @param array $attributes
     * @return string
     */
    private function getField(?string $value, array $attributes): string
    {
        return "<textarea ". $this->getHtmlFromArray($attributes) .">{$value}</textarea>";
    }

    /**
     * Build field's label
     *
     * @param string $key
     * @param string|null $label
     * @return string
     */
    private function getLabel(string $key, ?string $label = null): string
    {
        $label = (\is_null($label)) ? $key : $label;
        return "<label for=\"{$key}\">{$label}</label>";
    }
}

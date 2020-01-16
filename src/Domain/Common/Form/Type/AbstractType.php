<?php

namespace App\Domain\Common\Form\Type;

abstract class AbstractType implements TypeInterface
{
    /**
     * Transform array to HTML attributes
     * Example:
     * ['class' => 'form-control', 'id' => 'name']
     * class="form-control" id="name"
     *
     * @param array $attributes
     * @return string
     */
    protected function getHtmlFromArray(array $attributes): string
    {
        $html = [];

        foreach ($attributes as $key => $value) {
            if ($value === true) {
                $html[] = (string) $key;
            } elseif ($value !== false) {
                $html[] = "$key=\"$value\"";
            }
        }
        return implode(' ', $html);
    }

    /**
     * Make <div class="invalid-feedback">{$error}</div>
     * $error = error message from Validator
     *
     * @param array $errors
     * @param string $key
     * @return string
     */
    protected function getErrorHtml(array $errors, string $key): string
    {
        $error = $errors[$key] ?? false;
        if ($error) {
            return "<div class=\"invalid-feedback\">{$error}</div>";
        }

        return "";
    }
}

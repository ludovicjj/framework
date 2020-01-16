<?php

namespace App\Domain\Common\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FormExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('form_row', [$this, 'getForm'], ['is_safe' => ['html']])
        ];
    }

    public function getForm($index): string
    {
        return $index;
    }
}

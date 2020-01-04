<?php

namespace App\Domain\Common\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TimeExtension extends AbstractExtension
{
    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('ago', [$this, 'ago'], ['is_safe' => ['html']])
        ];
    }

    public function ago(\DateTime $date, string $format = 'd/m/Y H:i')
    {
        return '<time class="timeago" datetime="'. $date->format(\DateTime::ISO8601) .'">'.
            $date->format($format) .
            '</time>';
    }
}

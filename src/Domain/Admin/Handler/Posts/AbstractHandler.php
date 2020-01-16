<?php

namespace App\Domain\Admin\Handler\Posts;

abstract class AbstractHandler
{
    /**
     * Filter request body
     *
     * @param array $data
     * @return array
     */
    protected function getFilterParams(array $data): array
    {
        return array_filter($data, function ($key) {
            return in_array($key, ['name', 'slug', 'content']);
        }, ARRAY_FILTER_USE_KEY);
    }
}

<?php

namespace App\Domain\Admin\Handler\Posts;

use Psr\Http\Message\ServerRequestInterface;

abstract class AbstractHandler
{
    /**
     * Filter request body
     *
     * @param ServerRequestInterface $request
     * @return array
     */
    protected function getFilterParams(ServerRequestInterface $request): array
    {
        return array_filter($request->getParsedBody(), function ($key) {
            return in_array($key, ['name', 'slug', 'content']);
        }, ARRAY_FILTER_USE_KEY);
    }
}

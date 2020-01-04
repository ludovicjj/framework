<?php

namespace App\Domain\Common\Router\Utils;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

trait RouterAwareAction
{
    /**
     * Send a RedirectResponse with status code 301
     *
     * @param string $path
     * @param array $parameters
     * @return ResponseInterface
     */
    public function redirect(string $path, array $parameters = []): ResponseInterface
    {
        $redirectUri = $this->router->generateUri($path, $parameters);

        return (new Response())
            ->withStatus(301)
            ->withHeader('Location', $redirectUri);
    }
}

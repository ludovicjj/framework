<?php

namespace App\Responder;

use GuzzleHttp\Psr7\Response;

class RedirectResponse extends Response
{
    /**
     * Send Response with status 301.
     * Response implements PSR-7 responseInterface.
     *
     * RedirectResponse constructor.
     * @param string $path
     */
    public function __construct(
        string $path
    ) {
        parent::__construct(301, ['location' => $path]);
    }
}

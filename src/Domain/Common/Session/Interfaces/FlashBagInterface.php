<?php

namespace App\Domain\Common\Session\Interfaces;

interface FlashBagInterface
{
    /**
     * Define the key and key's message into $_SESSION
     *
     * @param string $key
     * @param string $message
     */
    public function add(string $key, string $message): void;

    /**
     * Get the key's message from $_SESSION
     *
     * @param string $key
     * @return string|null
     */
    public function get(string $key): ?string;
}

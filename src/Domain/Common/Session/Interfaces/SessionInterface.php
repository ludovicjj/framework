<?php

namespace App\Domain\Common\Session\Interfaces;

interface SessionInterface
{
    /**
     * Get value of key from $_SESSION.
     * If key doesn't exist in $_SESSION return $default.
     *
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * Define key and his value in session
     *
     * @param string $key
     * @param $value
     */
    public function set(string $key, $value): void;

    /**
     * Delete key from session
     *
     * @param string $key
     */
    public function delete(string $key): void;
}

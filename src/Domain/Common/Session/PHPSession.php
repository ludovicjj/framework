<?php

namespace App\Domain\Common\Session;

use App\Domain\Common\Session\Interfaces\SessionInterface;

class PHPSession implements SessionInterface
{
    /**
     * Get value of key from $_SESSION.
     * If key doesn't exist in $_SESSION return $default.
     *
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $this->ensureStarted();
        if (array_key_exists($key, $_SESSION)) {
            return $_SESSION[$key];
        }
        return $default;
    }

    /**
     * Define key and his value in session
     *
     * @param string $key
     * @param $value
     */
    public function set(string $key, $value): void
    {
        $this->ensureStarted();
        $_SESSION[$key] = $value;
    }

    /**
     * Delete key from session
     *
     * @param string $key
     */
    public function delete(string $key): void
    {
        $this->ensureStarted();
        unset($_SESSION[$key]);
    }

    /**
     * Ensure session is started
     */
    private function ensureStarted()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}

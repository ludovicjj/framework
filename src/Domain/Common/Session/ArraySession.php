<?php

namespace App\Domain\Common\Session;

use App\Domain\Common\Session\Interfaces\SessionInterface;

class ArraySession implements SessionInterface
{
    /**@var array */
    private $session = [];

    /**
     * Get value of key from $this->session.
     * If key doesn't exist in array return $default.
     *
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        if (array_key_exists($key, $this->session)) {
            return $this->session[$key];
        }
        return $default;
    }

    /**
     * Define key and his value in $this->session
     *
     * @param string $key
     * @param $value
     */
    public function set(string $key, $value): void
    {
        $this->session[$key] = $value;
    }

    /**
     * Delete key from $this->session
     *
     * @param string $key
     */
    public function delete(string $key): void
    {
        unset($this->session[$key]);
    }
}

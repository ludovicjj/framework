<?php

namespace App\Domain\Common\Session;

use App\Domain\Common\Session\Interfaces\FlashBagInterface;
use App\Domain\Common\Session\Interfaces\SessionInterface;

class FlashBag implements FlashBagInterface
{
    /** @var SessionInterface */
    private $session;

    /** @var string */
    const FLASH_SESSION_KEY = 'flash__';

    private $message;

    public function __construct(
        SessionInterface $session
    ) {
        $this->session = $session;
    }

    /**
     * Define the key and key's message into $_SESSION
     *
     * @param string $key
     * @param string $message
     */
    public function add(string $key, string $message): void
    {
        // TODO $flash = []
        $flash = $this->session->get(self::FLASH_SESSION_KEY, []);
        //TODO ['success'] = $message
        $flash[$key] = $message;
        //TODO $_SESSION['flash__'] = ['success' => $message]
        $this->session->set(self::FLASH_SESSION_KEY, $flash);
    }

    /**
     * Get the key's message from $_SESSION
     *
     * @param string $key
     * @return string|null
     */
    public function get(string $key): ?string
    {
        if (\is_null($this->message)) {
            $this->message = $this->session->get(self::FLASH_SESSION_KEY, []);
            $this->session->delete(self::FLASH_SESSION_KEY);
        }

        if (array_key_exists($key, $this->message)) {
            return $this->message[$key];
        }
        return null;
    }
}

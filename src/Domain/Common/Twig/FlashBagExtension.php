<?php

namespace App\Domain\Common\Twig;

use App\Domain\Common\Session\Interfaces\FlashBagInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FlashBagExtension extends AbstractExtension
{
    /** @var FlashBagInterface */
    private $flashBag;

    /**
     * FlashBagExtension constructor.
     * @param FlashBagInterface $session
     */
    public function __construct(
        FlashBagInterface $session
    ) {
        $this->flashBag = $session;
    }

    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('flash', [$this, 'flash'])
        ];
    }

    /**
     * @param string $key
     * @return null|string
     */
    public function flash(string $key)
    {
        return $this->flashBag->get($key);
    }
}

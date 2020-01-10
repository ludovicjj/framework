<?php

namespace App\Domain\Common\Session\Factory;

use App\Domain\Common\Session\FlashBag;
use App\Domain\Common\Session\Interfaces\SessionInterface;
use Psr\Container\ContainerInterface;

class FlashBagFactory
{
    /**
     * @param ContainerInterface $container
     * @return FlashBag|null
     */
    public function __invoke(ContainerInterface $container): ?FlashBag
    {
        if ($container->has(SessionInterface::class)) {
            return new FlashBag($container->get(SessionInterface::class));
        }
        return null;
    }
}

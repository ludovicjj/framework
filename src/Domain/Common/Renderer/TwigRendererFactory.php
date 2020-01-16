<?php

namespace App\Domain\Common\Renderer;

use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

class TwigRendererFactory
{
    public function __invoke(ContainerInterface $container): TwigRenderer
    {
        $path = $container->get('default.path');
        $loader = new FilesystemLoader($path);
        $twig = new Environment($loader, ['debug' => true]);

        //active debugExtension
        $twig->addExtension(new DebugExtension());

        if ($container->has('twig.extensions')) {
            foreach ($container->get('twig.extensions') as $extension) {
                $twig->addExtension($extension);
            }
        }

        return new TwigRenderer($twig);
    }
}

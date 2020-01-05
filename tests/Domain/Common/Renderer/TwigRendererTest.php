<?php

namespace Tests\Domain\Common\Renderer;

use App\Domain\Common\Renderer\TwigRenderer;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigRendererTest extends TestCase
{
    /** @var TwigRenderer */
    private $twigRenderer;

    public function setUp(): void
    {
        $loader = new FilesystemLoader(dirname(__DIR__).'/views');
        $twig = new Environment($loader, []);
        $this->twigRenderer = new TwigRenderer($twig);
    }

    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function testRenderDefaultPath()
    {
        $content = $this->twigRenderer->render('demo.php');
        $this->assertEquals('Silence is golden', $content);
    }

    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function testRenderPath()
    {
        $this->twigRenderer->addPath(dirname(__DIR__).'/views', 'demo');
        $content = $this->twigRenderer->render('@demo/demo.php');

        $this->assertEquals('Silence is golden', $content);
    }

    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function testRenderWithParameters()
    {
        $content = $this->twigRenderer->render(
            'demo_params.html.twig',
            [
                'name' => 'john'
            ]
        );

        $this->assertEquals('Your name is john', $content);
    }

    /**
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function testGlobalParameters()
    {
        $this->twigRenderer->addGlobal('name', 'john');
        $content = $this->twigRenderer->render('demo_params.html.twig');

        $this->assertEquals('Your name is john', $content);
    }
}

<?php

namespace Tests\Framework\Renderer;

use Framework\Renderer\TwigRenderer;
use PHPUnit\Framework\TestCase;

class TwigRendererTest extends TestCase
{
    /** @var TwigRenderer */
    private $twigRenderer;

    public function setUp(): void
    {
        $this->twigRenderer = new TwigRenderer(dirname(__DIR__).'/views');
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

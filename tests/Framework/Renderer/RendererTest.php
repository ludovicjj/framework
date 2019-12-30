<?php

namespace Tests\Framework\Renderer;

use Framework\Renderer\Renderer;
use PHPUnit\Framework\TestCase;

class RendererTest extends TestCase
{
    /** @var Renderer */
    private $renderer;

    public function setUp(): void
    {
        $this->renderer = new Renderer();
    }

    public function testRenderDefaultPath()
    {
        $this->renderer->addPath(dirname(__DIR__).'/views');
        $content = $this->renderer->render('demo.php');

        $this->assertEquals('Silence is golden', $content);
    }

    public function testRenderPath()
    {
        $this->renderer->addPath(dirname(__DIR__).'/views', 'demo');
        $content = $this->renderer->render('@demo/demo.php');

        $this->assertEquals('Silence is golden', $content);
    }

    public function testRenderWithParameters()
    {
        $this->renderer->addPath(dirname(__DIR__).'/views', 'demo');
        $content = $this->renderer->render(
            '@demo/demoparams.php',
            [
                'name' => 'john'
            ]
        );

        $this->assertEquals('Your name is john', $content);
    }

    public function testGlobalParameters()
    {
        $this->renderer->addPath(dirname(__DIR__).'/views', 'demo');
        $this->renderer->addGlobal('name', 'john');
        $content = $this->renderer->render('@demo/demoparams.php');

        $this->assertEquals('Your name is john', $content);
    }
}

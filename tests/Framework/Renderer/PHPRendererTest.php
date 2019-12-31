<?php

namespace Tests\Framework\Renderer;

use Framework\Renderer\PHPRenderer;
use PHPUnit\Framework\TestCase;

class PHPRendererTest extends TestCase
{
    /** @var PHPRenderer */
    private $renderer;

    public function setUp(): void
    {
        $this->renderer = new PHPRenderer(dirname(__DIR__).'/views');
    }

    public function testRenderDefaultPath()
    {
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
        $content = $this->renderer->render(
            'demoparams.php',
            [
                'name' => 'john'
            ]
        );

        $this->assertEquals('Your name is john', $content);
    }

    public function testGlobalParameters()
    {
        $this->renderer->addGlobal('name', 'john');
        $content = $this->renderer->render('demoparams.php');

        $this->assertEquals('Your name is john', $content);
    }
}

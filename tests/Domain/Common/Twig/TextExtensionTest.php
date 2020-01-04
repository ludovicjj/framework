<?php

namespace Tests\Domain\Common\Twig;

use App\Domain\Common\Twig\TextExtension;
use PHPUnit\Framework\TestCase;

class TextExtensionTest extends TestCase
{
    /** @var TextExtension */
    private $textExtension;

    public function setUp(): void
    {
        $this->textExtension = new TextExtension();
    }

    public function testExcerptWthShortText()
    {
        $content = 'hello';
        $excerpt = $this->textExtension->excerpt($content, 10);
        $this->assertEquals('hello', $excerpt);
    }

    public function testExcerptWthLongText()
    {
        $content = 'hello world is a test';
        $excerpt = $this->textExtension->excerpt($content, 15);
        $this->assertEquals('hello world is...', $excerpt);
    }
}

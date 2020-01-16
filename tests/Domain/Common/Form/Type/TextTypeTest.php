<?php

namespace Tests\Domain\Common\Form\Type;

use App\Domain\Common\Form\Type\TextType;
use PHPUnit\Framework\TestCase;

class TextTypeTest extends TestCase
{
    /** @var TextType */
    private $textType;

    public function setUp(): void
    {
        $this->textType = new TextType();
    }

    public function testTextTypeWithPlaceholder()
    {
        $html = $this->textType->createField(
            [],
            'name',
            'demo',
            [
                'label' => 'Titre',
                'attr' => [
                    'placeholder' => 'hello'
                ]
            ]
        );

        $this->assertSimilar(
            "<div class=\"form-group\">
            <label for=\"name\">Titre</label>
            <input type=\"text\" class=\"form-control\" id=\"name\" name=\"name\" placeholder=\"hello\" value=\"demo\">
            </div>",
            $html
        );
    }

    public function testTextTypeWithoutPlaceholder()
    {
        $html = $this->textType->createField(
            [],
            'name',
            'demo',
            [
                'label' => 'Titre',
                'attr' => []
            ]
        );

        $this->assertSimilar(
            "<div class=\"form-group\">
            <label for=\"name\">Titre</label>
            <input type=\"text\" class=\"form-control\" id=\"name\" name=\"name\" value=\"demo\">
            </div>",
            $html
        );
    }

    public function testTextTypeWithError()
    {
        $html = $this->textType->createField(
            ['name' => 'field is not valid'],
            'name',
            'demo',
            [
                'label' => 'Titre',
            ]
        );

        $this->assertSimilar(
            "<div class=\"form-group\">
            <label for=\"name\">Titre</label>
            <input type=\"text\" class=\"form-control is-invalid\" id=\"name\" name=\"name\" value=\"demo\">
            <div class=\"invalid-feedback\">field is not valid</div>
            </div>",
            $html
        );
    }

    private function assertSimilar(string $expected, string $actual)
    {
        $this->assertEquals($this->trim($expected), $this->trim($actual));
    }

    private function trim(string $string)
    {
        $lines = explode("\n", $string);
        $lines =  array_map('trim', $lines);
        return implode('', $lines);
    }
}

<?php

namespace Tests\Domain\Common\Form\Type;

use App\Domain\Common\Form\Type\TextareaType;
use PHPUnit\Framework\TestCase;

class TextareaTypeTest extends TestCase
{
    /** @var TextareaType */
    private $type;

    public function setUp(): void
    {
        $this->type = new TextareaType();
    }

    public function testCreateField()
    {
        $html = $this->type->createField(
            [],
            'name',
            'demo',
            [
                'label' => 'Titre',
                'attr' => [
                    'rows' => '10',
                    'placeholder' => 'test'
                ]
            ]
        );

        $this->assertSimilar(
            "<div class=\"form-group\">
            <label for=\"name\">Titre</label>
            <textarea class=\"form-control\" id=\"name\" name=\"name\" rows=\"10\" placeholder=\"test\">demo</textarea>
            </div>",
            $html
        );
    }

    public function testCreateFieldWithoutAttributes()
    {
        $html = $this->type->createField(
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
            <textarea class=\"form-control\" id=\"name\" name=\"name\" rows=\"4\">demo</textarea>
            </div>",
            $html
        );
    }

    public function testCreateFieldWithError()
    {
        $html = $this->type->createField(
            ['name' => 'my error'],
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
            <textarea class=\"form-control is-invalid\" id=\"name\" name=\"name\" rows=\"4\">demo</textarea>
            <div class=\"invalid-feedback\">my error</div>
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

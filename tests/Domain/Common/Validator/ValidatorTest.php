<?php

namespace Tests\Domain\Common\Validator;

use App\Domain\Common\Validator\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    public function testRequiredFailWithCustomErrorMessage()
    {
        $errors = $this->makeValidator(
            [
                'name' => 'john'
            ]
        )
            ->required([
                'name' => 'name',
                'message' => 'Le champs titre est requis'
            ])
            ->required([
                'name' => 'content',
                'message' => 'Le champs description est requis'
            ])
            ->getErrors()
        ;
        $this->assertCount(1, $errors);
        $this->assertEquals('Le champs description est requis', $errors['content']);
    }

    public function testRequiredFailWithDefaultErrorMessage()
    {
        $errors = $this->makeValidator(
            [
                'name' => 'john'
            ]
        )
            ->required([
                'name' => 'name',
                'message' => 'Le champs titre est requis'
            ])
            ->required([
                'name' => 'content'
            ])
            ->getErrors()
        ;
        $this->assertCount(1, $errors);
        $this->assertEquals('Le champs content est requis', $errors['content']);
    }

    public function testRequiredSuccess()
    {
        $errors = $this->makeValidator(
            [
                'name' => 'john',
                'content' => 'My awesome content'
            ]
        )
            ->required(['name' => 'name'])
            ->required(['name' => 'content'])
            ->getErrors()
        ;
        $this->assertCount(0, $errors);
    }

    public function testSlugSuccess()
    {
        $errors = $this->makeValidator(
            [
                'slug' => 'aze-azaze-78'
            ]
        )
            ->slug(['name' => 'slug'])
            ->getErrors();
        $this->assertCount(0, $errors);
    }

    public function testSlugFail()
    {
        $errors = $this->makeValidator(
            [
                'slug' => 'Aze-azaze-78',
                'slug2' => 'aze_azaze-78',
                'slug3' => 'aze--azaze-78',
                'slug4' => 'aze-azazé-78'
            ]
        )
            ->slug(['name' => 'slug'])
            ->slug(['name' => 'slug5'])
            ->slug(['name' => 'slug2'])
            ->slug(['name' => 'slug3'])
            ->slug(
                [
                    'name' => 'slug4',
                    'message' => 'Slug incorrect, exemple: "mon-super-slug"'
                ]
            )
            ->getErrors();
        $this->assertCount(4, $errors);
        $this->assertEquals('Slug incorrect, exemple: "mon-super-slug"', $errors['slug4']);
        $this->assertEquals('Le champs slug3 n\'est pas un slug valide', $errors['slug3']);
    }

    public function testNotBlankFail()
    {
        $errors = $this->makeValidator(
            [
                'name' => 'john',
                'content' => ''
            ]
        )
            ->notBlank(['name' => 'name'])
            ->notBlank(['name' => 'content'])
            ->notBlank(['name' => 'leviathan'])
            ->getErrors();
        $this->assertCount(1, $errors);
    }

    public function testMaxLengthFailWithDefaultMessage()
    {
        $errors = $this->makeValidator(['name' => '123456789'])
            ->length(
                [
                    'name' => 'name',
                    'max' => 8
                ]
            )
            ->length(
                [
                    'name' => 'demo',
                    'max' => 8
                ]
            )
            ->getErrors();

        $this->assertCount(1, $errors);
        $this->assertEquals(
            'Le champs name doit contenir moins de 8 caractères',
            $errors['name']->__toString()
        );
    }

    public function testMaxLengthFailWithCustomMessage()
    {
        $errors = $this->makeValidator(['name' => '123456789'])
            ->length(
                [
                    'name' => 'name',
                    'max' => 8,
                    'maxMessage' => 'My custom message for MaxLength'
                ]
            )
            ->getErrors();

        $this->assertCount(1, $errors);
        $this->assertEquals(
            'My custom message for MaxLength',
            $errors['name']->__toString()
        );
    }

    public function testMinLengthFailWithDefaultMessage()
    {
        $errors = $this->makeValidator(['name' => '123456789'])
            ->length(
                [
                    'name' => 'name',
                    'min' => 10
                ]
            )
            ->getErrors();

        $this->assertCount(1, $errors);
        $this->assertEquals(
            'Le champs name doit contenir plus de 10 caractères',
            $errors['name']->__toString()
        );
    }

    public function testMinLengthFailWithCustomMessage()
    {
        $errors = $this->makeValidator(['name' => '123456789'])
            ->length(
                [
                    'name' => 'name',
                    'min' => 10,
                    'minMessage' => 'My custom message for MinLength'
                ]
            )
            ->getErrors();

        $this->assertCount(1, $errors);
        $this->assertEquals(
            'My custom message for MinLength',
            $errors['name']->__toString()
        );
    }

    public function testBetweenLengthFailWithDefaultMessage()
    {
        $errors = $this->makeValidator(['name' => '123'])
            ->length(
                [
                    'name' => 'name',
                    'min' => 4,
                    'max' => 7
                ]
            )
            ->getErrors();

        $this->assertCount(1, $errors);
        $this->assertEquals(
            'Le champs name doit contenir entre 4 et 7 caractères',
            $errors['name']->__toString()
        );
    }

    public function testBetweenLengthFailWithCustomMessage()
    {
        $errors = $this->makeValidator(['name' => '123'])
            ->length(
                [
                    'name' => 'name',
                    'min' => 4,
                    'max' => 7,
                    'betweenMessage' => 'My custom message for between'
                ]
            )
            ->getErrors();

        $this->assertCount(1, $errors);
        $this->assertEquals(
            'My custom message for between',
            $errors['name']->__toString()
        );
    }

    public function testDateTime()
    {
        $error = $this->makeValidator(
            [
                'date' => '2012-12-12 11:12:13',
                'date2' => '2012-12-12',
                'date3' => '2012-21-12',
                'date4' => '2013-02-29 11:12:13'

            ]
        )
            ->dateTime(['name' => 'date'])
            ->dateTime(['name' => 'date2'])
            ->dateTime(['name' => 'date3'])
            ->dateTime(['name' => 'date4'])
            ->getErrors();
        $this->assertCount(3, $error);
    }

    private function makeValidator(array $data)
    {
        return new Validator($data);
    }
}

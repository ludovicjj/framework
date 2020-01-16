<?php

namespace App\Domain\Admin\Form;

use App\Domain\Common\Form\AbstractType;
use App\Domain\Common\Form\FormBuilder;
use App\Domain\Common\Form\Type\TextareaType;
use App\Domain\Common\Form\Type\TextType;
use App\Domain\Common\Validator\Validator;

class AddPostType extends AbstractType
{
    public function buildForm(FormBuilder $builder)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'placeholder' => 'Votre titre'
                ]
            ])
            ->add('slug', TextType::class, [
                'label' => 'Slug',
                'attr' => [
                    'placeholder' => 'Slug de l\'article'
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'rows' => '10'
                ]
            ])
        ;
    }

    public function buildValidator(Validator $validator)
    {
        $validator
            ->required(
                [
                    'name' => 'name',
                    'message' => 'Le champs titre est requis'
                ]
            )
            ->required(
                [
                    'name' => 'slug',
                    'message' => 'Le champs slug est requis'
                ]
            )
            ->required(
                [
                    'name' => 'content',
                    'message' => 'Le champs titre est requis'
                ]
            )
            ->length(
                [
                    'name' => 'name',
                    'min' => 4,
                    'max' => 30,
                    'betweenMessage' => 'Le champs titre doit contenir entre 4 et 30 caractères'
                ]
            )
            ->length(
                [
                    'name' => 'slug',
                    'min' => 4,
                    'max' => 20,
                    'betweenMessage' => 'Le champs Slug doit contenir entre 4 et 20 caractères'
                ]
            )
            ->length(
                [
                    'name' => 'content',
                    'min' => 10,
                    'minMessage' => 'Le champs Description doit contenir minimum 10 caractères'
                ]
            )
        ;
    }
}

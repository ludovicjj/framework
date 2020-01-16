<?php

namespace App\Domain\Common\Form;

use App\Domain\Common\Form\Interfaces\FormInterface;
use App\Domain\Common\Validator\Validator;
use Psr\Http\Message\ServerRequestInterface;

class Form implements FormInterface
{
    /** @var AbstractType|null */
    private $type;

    /** @var string */
    private $method;

    /** @var FormBuilder */
    private $builder;

    /** @var array */
    private $view = [];

    /** @var Validator */
    private $validator;

    /** @var array */
    private $parseBody;

    /**
     * Form constructor.
     * @param string $type
     * @param object|null $entity
     */
    public function __construct(string $type, ?object $entity = null)
    {
        $this->type = $this->initType($type);
        $this->builder = $this->makeFormBuilder();
        if (!\is_null($entity)) {
            $this->builder->addEntity($entity);
        }
    }

    /**
     * @param ServerRequestInterface $request
     * @return FormInterface
     */
    public function handleRequest(ServerRequestInterface $request): FormInterface
    {
        $this->method = $request->getMethod();
        $this->parseBody = $request->getParsedBody();
        $this->validator = $this->makeValidator();
        return $this;
    }

    /**
     * @return bool
     */
    public function isSubmitted(): bool
    {
        if ($this->method === 'POST') {
            if (method_exists($this->type, 'buildValidator')) {
                $this->type->buildValidator($this->validator);
                $this->builder->addErrors($this->validator->getErrors());
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->validator->isValid();
    }

    /**
     * @return array
     */
    public function createView(): array
    {
        if (method_exists($this->type, 'buildForm')) {
            $this->type->buildForm($this->builder);
            $this->view = $this->builder->getFields();
        }

        return $this->view;
    }

    /**
     * @param string|null $key
     * @return array|string
     */
    public function getData(?string $key = null)
    {
        if (!\is_null($key) && array_key_exists($key, $this->parseBody)) {
            return $this->parseBody[$key];
        }
        return $this->parseBody;
    }


    /**
     * Build Validator
     *
     * @return Validator
     */
    private function makeValidator(): Validator
    {
        return new Validator($this->parseBody);
    }

    /**
     * Build Validator
     *
     * @return FormBuilder
     */
    private function makeFormBuilder(): FormBuilder
    {
        return new FormBuilder();
    }

    /**
     * @param string $type
     * @return AbstractType|null
     */
    private function initType(string $type): ?AbstractType
    {
        if (class_exists($type)) {
            $type = new $type();
            return ($type instanceof AbstractType) ? $type : null;
        }
        return null;
    }
}

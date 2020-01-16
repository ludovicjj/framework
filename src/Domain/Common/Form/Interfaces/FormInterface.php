<?php

namespace App\Domain\Common\Form\Interfaces;

use Psr\Http\Message\ServerRequestInterface;

interface FormInterface
{
    /**
     * Get method from Request.
     * Get parseBody from Request
     * Init Validator with parseBody from request.
     *
     * Method use Fluent pattern.
     *
     * @param ServerRequestInterface $request
     * @return FormInterface
     */
    public function handleRequest(ServerRequestInterface $request): self;

    /**
     * Check Request's method,
     * If method is "POST" :
     * Init validator'constraints from my form in method buildValidator().
     * And send errors to FormBuilder, return true.
     *
     * If method is "GET" :
     * Just return false.
     *
     * @return bool
     */
    public function isSubmitted(): bool;

    /**
     * Check if Validator return an empty array of errors
     *
     * @return bool
     */
    public function isValid(): bool;

    /**
     * Check if AbstractForm has method buildForm().
     * Define property view with result of method buildForm().
     * Return property view with all field or empty
     *
     * @return array
     */
    public function createView(): array;

    /**
     * Return parse body or value if key is defined
     *
     * @param string|null $key
     * @return mixed
     */
    public function getData(?string $key = null);
}

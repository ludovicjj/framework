<?php

namespace App\Domain\Common\Validator;

class ValidatorErrorBuilder
{
    /** @var string */
    private $key;

    /** @var string */
    private $rule;

    /** @var array */
    private $attributes;

    private $messages = [
        'required'      => 'Le champs %s est requis',
        'slug'          => 'Le champs %s n\'est pas un slug valide',
        'blank'         => 'Le champs %s ne doit pas être vide',
        'minLength'     => 'Le champs %s doit contenir plus de %d caractères',
        'maxLength'     => 'Le champs %s doit contenir moins de %d caractères',
        'betweenLength' => 'Le champs %s doit contenir entre %d et %d caractères',
        'datetime'      => 'Le champs %s doit être une date valide (%s)'
    ];

    /**
     * ValidatorErrorBuilder constructor.
     * @param string $key
     * @param string $rule
     * @param string|null $message
     * @param array $attributes
     */
    public function __construct(
        string $key,
        string $rule,
        ?string $message = null,
        array $attributes = []
    ) {
        $this->key = $key;
        $this->rule = $rule;
        $this->attributes = $attributes;
        if (!\is_null($message)) {
            $this->messages[$this->rule] = $message;
        }
    }

    public function __toString(): string
    {
        /** @var array $errorParams */
        $errorParams = array_merge([$this->messages[$this->rule], $this->key], $this->attributes);
        return call_user_func_array('sprintf', $errorParams);
    }
}

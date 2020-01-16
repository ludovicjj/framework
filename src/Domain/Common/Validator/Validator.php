<?php

namespace App\Domain\Common\Validator;

class Validator
{
    /** @var array */
    private $data;

    /** @var array */
    private $errors = [];


    public function __construct(array $data)
    {
        $this->data = $data;
    }


    /**
     * Check if key exist
     * Method use Fluent pattern
     *
     * @param array $param
     * @return Validator
     */
    public function required(array $param): self
    {
        $key = $param['name'];
        $message = $param['message'] ?? null;

        if (\is_null($this->getValue($key))) {
            $this->addError($key, 'required', $message);
        }

        return $this;
    }

    /**
     * Check if value is a valid slug
     * Method use Fluent pattern
     *
     * @param array $param
     * @return Validator
     */
    public function slug(array $param): self
    {
        $pattern = '/^[a-z0-9]+(-[a-z0-9]+)*$/';
        $key = $param['name'];
        $message = $param['message'] ?? null;
        $value = $this->getValue($key);

        if (!\is_null($value) && !\preg_match($pattern, $value)) {
            $this->addError($key, 'slug', $message);
        }

        return $this;
    }

    /**
     * Check if value is blank
     * Method use Fluent pattern
     *
     * @param array $param
     * @return Validator
     */
    public function notBlank(array $param): self
    {
        $key = $param['name'];
        $message = $param['message'] ?? null;
        $value = $this->getValue($key);

        if (!\is_null($value) && empty($value)) {
            $this->addError($key, 'blank', $message);
        }

        return $this;
    }

    public function length(array $param): self
    {
        $key = $param['name'];
        $min = $param['min'] ?? null;
        $max = $param['max'] ?? null;

        if (\is_null($this->getValue($key))) {
            return $this;
        }

        $length = mb_strlen($this->getValue($key));

        // TODO case : between constraint
        if (!\is_null($min) &&
            !\is_null($max) &&
            ($length < $min || $length > $max)
        ) {
            $this->addError($key, 'betweenLength', $param['betweenMessage'] ?? null, [$min, $max]);
        }

        // TODO case : min constraint
        if (!\is_null($min) &&
            \is_null($max) &&
            $length < $min
        ) {
            $this->addError($key, 'minLength', $param['minMessage'] ?? null, [$min]);
        }

        // TODO case : max constraint
        if (!\is_null($max) &&
            \is_null($min) &&
            $length > $max
        ) {
            $this->addError($key, 'maxLength', $param['maxMessage'] ?? null, [$max]);
        }

        return $this;
    }

    public function dateTime(array $param, string $format = 'Y-m-d H:i:s'): self
    {
        $key = $param['name'];
        $value = $this->getValue($key);

        if (\is_null($value)) {
            return $this;
        }

        $date = \DateTime::createFromFormat($format, $value);
        $error = \DateTime::getLastErrors();

        if ($error['error_count'] > 0 || $error['warning_count'] > 0 || $date === false) {
            $this->addError($key, 'datetime', $param['message'] ?? null, [$format]);
        }

        return $this;
    }

    public function isValid(): bool
    {
        return empty($this->getErrors());
    }

    /**
     * Get error(s)
     *
     * @return ValidatorErrorBuilder[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param string $key
     * @param string $rule
     * @param string|null $message
     * @param array $attribute
     */
    private function addError(
        string $key,
        string $rule,
        ?string $message = null,
        array $attribute = []
    ): void {
        $this->errors[$key] = new ValidatorErrorBuilder($key, $rule, $message, $attribute);
    }

    /**
     * Get key's value
     *
     * @param string $key
     * @return string|null
     */
    private function getValue(string $key): ?string
    {
        if (\array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }
        return null;
    }
}

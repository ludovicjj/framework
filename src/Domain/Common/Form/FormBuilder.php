<?php

namespace App\Domain\Common\Form;

use App\Domain\Common\Form\Type\TypeInterface;

class FormBuilder
{
    /** @var array */
    private $fields = [];

    /** @var array */
    private $errors= [];

    private $entity;

    /**
     * Add field
     *
     * @param string $name
     * @param string $type
     * @param array $options
     * @return FormBuilder
     */
    public function add(string $name, string $type, array $options): self
    {
        /** @var TypeInterface $type */
        $type = new $type();

        //TODO get value from entity
        $value = $this->getValue($name);

        //TODO lance la method createField
        $field = $type->createField($this->errors, $name, $value, $options);

        $this->fields[$name] = $field;

        return $this;
    }

    /**
     * Return array with all fields
     *
     * @return array
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * Get errors from Validator
     *
     * @param array $errors
     */
    public function addErrors(array $errors): void
    {
        $this->errors = $errors;
    }

    /**
     * Define entity
     *
     * @param object $entity
     */
    public function addEntity(object $entity): void
    {
        $this->entity = $entity;
    }

    /**
     * Get value from entity
     *
     * @param string $name
     * @return string|null
     */
    private function getValue(string $name): ?string
    {
        if (!\is_null($this->entity)) {
            if ($this->reflexionProperty($name)) {
                return $this->entity->{$name};
            }

            $method = 'get'.ucfirst($name);
            if ($this->reflexionMethod($method)) {
                return $this->entity->{$method}();
            }
        }
        return null;
    }

    /**
     * Check if property exist and visibility is public
     *
     * @param string $name
     * @return bool
     */
    private function reflexionProperty(string $name): bool
    {
        try {
            $reflexion = new \ReflectionProperty($this->entity, $name);
        } catch (\ReflectionException $exception) {
            return false;
        }

        if (property_exists($this->entity, $name) && $reflexion->isPublic()) {
            return true;
        }
        return false;
    }

    /**
     * Check if method exist and visibility is public
     *
     * @param string $method
     * @return bool
     */
    private function reflexionMethod(string $method)
    {
        try {
            $reflexion = new \ReflectionMethod($this->entity, $method);
        } catch (\ReflectionException $exception) {
            return false;
        }

        if (method_exists($this->entity, $method) && $reflexion->isPublic()) {
            return true;
        }
        return false;
    }
}

<?php

namespace Fabs\Serialize\Validation;

abstract class ValidationBase
{
    /**
     * @var bool
     */
    protected $is_required = false;
    /**
     * @var bool
     */
    protected $is_array = false;

    /**
     * @param bool $is_required
     * @return ValidationBase
     */
    public function isRequired($is_required = true)
    {
        $this->is_required = $is_required;
        return $this;
    }

    /**
     * @param bool $is_array
     * @return ValidationBase
     */
    public function isArray($is_array = true)
    {
        $this->is_array = $is_array;
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsRequired()
    {
        return $this->is_required;
    }

    /**
     * @return bool
     */
    public function getIsArray()
    {
        return $this->is_array;
    }

    /**
     * @return string
     */
    public abstract function getValidationName();

    /**
     * @param $value mixed
     * @return bool
     */
    public abstract function isValid($value);
}
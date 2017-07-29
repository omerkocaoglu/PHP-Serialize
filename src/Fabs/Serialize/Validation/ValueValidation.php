<?php
/**
 * Created by PhpStorm.
 * User: necipalllef
 * Date: 02/07/2017
 * Time: 12:55
 */

namespace Fabs\Serialize\Validation;


class ValueValidation extends ValidationBase
{
    /** @var array  */
    private $valid_values = [];

    function __construct($values = [])
    {
        $this->valid_values = $values;
    }

    /**
     * @return string
     */
    public function getValidationName()
    {
        return implode('|', $this->valid_values);
    }

    /**
     * @param mixed $value
     */
    public function addValue($value)
    {
        $this->valid_values[] = $value;
    }

    /**
     * @param array $values
     */
    public function setValues($values)
    {
        $this->valid_values = $values;
    }

    /**
     * @param $value mixed
     * @return bool
     */
    public function isValid($value)
    {
        if ($value === null) {
            if ($this->is_required) {
                return false;
            } else {
                return true;
            }
        }

        return in_array($value, $this->valid_values, true);
    }
}
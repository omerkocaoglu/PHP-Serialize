<?php
/**
 * Created by PhpStorm.
 * User: ahmetturk
 * Date: 15/04/2017
 * Time: 16:21
 */

namespace Fabs\Serialize\Validation;

class BooleanValidation extends ValidationBase
{
    public function isValid($value)
    {
        if ($value == null) {
            if ($this->is_required) {
                return false;
            } else {
                return true;
            }
        }
        return is_bool($value);
    }

    /**
     * @return string
     */
    public function getValidationName()
    {
        return 'boolean';
    }
}
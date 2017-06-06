<?php
/**
 * Created by PhpStorm.
 * User: ahmetturk
 * Date: 15/04/2017
 * Time: 16:21
 */

namespace Fabs\Serialize\Validation;


class FloatValidation extends ValidationBase
{

    public function isValid($value)
    {
        if ($value === null) {
            if ($this->is_required) {
                return false;
            } else {
                return true;
            }
        }

        return is_float($value) || is_int($value);
    }

    public function getValidationName()
    {
        return 'float';
    }
}
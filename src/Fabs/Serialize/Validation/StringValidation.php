<?php
/**
 * Created by PhpStorm.
 * User: ahmetturk
 * Date: 15/04/2017
 * Time: 16:23
 */

namespace Fabs\Serialize\Validation;


class StringValidation extends ValidationBase
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

        return is_string($value);
    }

    public function getValidationName()
    {
        return 'string';
    }
}
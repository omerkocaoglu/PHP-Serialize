<?php
/**
 * Created by PhpStorm.
 * User: ahmetturk
 * Date: 15/04/2017
 * Time: 16:22
 */

namespace Fabs\Serialize\Validation;


class ObjectValidation extends ValidationBase
{
    /** @var string */
    protected $type = null;

    /**
     * @param $type string
     * @return ObjectValidation
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function isValid($value)
    {
        if ($value == null) {
            if ($this->is_required) {
                return false;
            } else {
                return true;
            }
        }

        return is_a($value, $this->type);
    }

    public function getValidationName()
    {
        return $this->type;
    }
}

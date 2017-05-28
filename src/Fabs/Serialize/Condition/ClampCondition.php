<?php
/**
 * Created by PhpStorm.
 * User: ahmetturk
 * Date: 28/05/2017
 * Time: 11:11
 */

namespace Fabs\Serialize\Condition;


class ClampCondition extends ConditionBase
{
    protected $max_value = null;
    protected $min_value = null;

    public function apply($value)
    {
        if ($this->max_value !== null) {
            if ($value > $this->max_value) {
                $this->setNewValue($this->max_value);
            }
        }

        if ($this->min_value !== null) {
            if ($value < $this->min_value) {
                $this->setNewValue($this->min_value);
            }
        }
    }

    /**
     * @param $value int|null
     * @return ClampCondition
     */
    public function setMax($value)
    {
        $this->max_value = $value;
        return $this;
    }

    /**
     * @param $value int|null
     * @return ClampCondition
     */
    public function setMin($value)
    {
        $this->min_value = $value;
        return $this;
    }
}
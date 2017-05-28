<?php
/**
 * Created by PhpStorm.
 * User: ahmetturk
 * Date: 28/05/2017
 * Time: 10:35
 */

namespace Fabs\Serialize\Condition;


abstract class ConditionBase
{
    protected $should_render = true;
    protected $should_value_update = false;
    protected $new_value = null;

    public abstract function apply($value);

    public function getShouldRender()
    {
        return $this->should_render;
    }

    public function getShouldValueUpdate()
    {
        return $this->should_value_update;
    }

    public function getNewValue()
    {
        return $this->new_value;
    }

    protected function setNewValue($value)
    {
        $this->new_value = $value;
        $this->should_value_update = true;
    }
}
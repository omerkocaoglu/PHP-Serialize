<?php

namespace Fabs\Serialize\Condition;


class RenderIfNotEmptyArrayCondition extends ConditionBase
{
    public function apply($value)
    {
        if ($value === null || count($value) === 0) {
            $this->should_render = false;
        } else {
            $this->should_render = true;
        }
    }
}
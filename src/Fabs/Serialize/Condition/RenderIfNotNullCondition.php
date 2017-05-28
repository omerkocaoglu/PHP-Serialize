<?php
/**
 * Created by PhpStorm.
 * User: ahmetturk
 * Date: 28/05/2017
 * Time: 10:39
 */

namespace Fabs\Serialize\Condition;


class RenderIfNotNullCondition extends ConditionBase
{
    public function apply($value)
    {
        if ($value == null) {
            $this->should_render = false;
        } else {
            $this->should_render = true;
        }
    }
}
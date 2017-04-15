<?php
/**
 * Created by PhpStorm.
 * User: ahmetturk
 * Date: 15/04/2017
 * Time: 16:02
 */

namespace Fabs\Serialize;


class RegisteredProperty
{
    private $class_name = '';
    private $is_array = false;

    public function __construct($class_name, $is_array)
    {
        $this->class_name = $class_name;
        $this->is_array = $is_array;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->class_name;
    }

    /**
     * @return bool
     */
    public function getIsArray()
    {
        return $this->is_array;
    }
}
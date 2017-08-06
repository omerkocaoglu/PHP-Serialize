<?php
/**
 * Created by PhpStorm.
 * User: ahmetturk
 * Date: 15/04/2017
 * Time: 16:20
 */

namespace Fabs\Serialize\Validation;

class ValidationException extends \Exception
{

    /** @var string */
    protected $class_name;
    /** @var string */
    protected $property_name;
    /** @var string */
    protected $validator_name;
    /** @var mixed */
    public $given_value = '';


    /**
     * ValidationException constructor.
     * @param string $class_name
     * @param string $property_name
     * @param string $validator_name
     * @param mixed $given_value
     */
    public function __construct($class_name, $property_name, $validator_name, $given_value = '')
    {
        $this->class_name = $class_name;
        $this->property_name = $property_name;
        $this->validator_name = $validator_name;
        $this->given_value = $given_value;

        parent::__construct('Validation failed', 0, null);
    }


    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->class_name;
    }


    /**
     * @return string
     */
    public function getPropertyName()
    {
        return $this->property_name;
    }


    /**
     * @return string
     */
    public function getValidatorName()
    {
        return $this->validator_name;
    }


    /**
     * @return mixed|string
     */
    public function getGivenValue()
    {
        return $this->given_value;
    }
}
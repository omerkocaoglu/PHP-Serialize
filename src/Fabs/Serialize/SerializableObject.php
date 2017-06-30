<?php

namespace Fabs\Serialize;

use Fabs\Serialize\Condition\ClampCondition;
use Fabs\Serialize\Condition\ConditionBase;
use Fabs\Serialize\Condition\RenderIfNotNullCondition;
use Fabs\Serialize\Validation\BooleanValidation;
use Fabs\Serialize\Validation\FloatValidation;
use Fabs\Serialize\Validation\IntegerValidation;
use Fabs\Serialize\Validation\ObjectValidation;
use Fabs\Serialize\Validation\StringValidation;
use Fabs\Serialize\Validation\ValidationBase;
use Fabs\Serialize\Validation\ValidationException;

class SerializableObject implements \JsonSerializable
{
    /**
     * @var RegisteredProperty[]
     */
    protected $serializable_object_registered_properties = [];
    /**
     * @var ValidationBase[][]
     */
    protected $serializable_object_validations = [];
    /**
     * @var string[]
     */
    protected $serializable_object_transient_properties = [];
    /**
     * @var ConditionBase[][]
     */
    protected $serializable_object_conditions = [];

    public function __construct()
    {
        $this->makeTransient('serializable_object_registered_properties');
        $this->makeTransient('serializable_object_validations');
        $this->makeTransient('serializable_object_transient_properties');
        $this->makeTransient('serializable_object_conditions');
    }

    public function jsonSerialize()
    {
        $this->validate();

        $output = [];

        foreach ($this as $property_name => $value) {

            if (in_array($property_name, $this->serializable_object_transient_properties)) {
                continue;
            }

            $should_render = $this->applyConditions($property_name, $value);
            if (!$should_render) {
                continue;
            }

            if ($value instanceof SerializableObject) {
                $output[$property_name] = $value->jsonSerialize();
            } else {
                if (is_array($value)) {
                    $output[$property_name] = [];
                    foreach ($value as $key2 => $value2) {
                        if ($value2 instanceof SerializableObject) {
                            $output[$property_name][$key2] = $value2->jsonSerialize();
                        } else {
                            $output[$property_name][$key2] = $value2;
                        }
                    }
                } else {
                    $output[$property_name] = $value;
                }
            }
        }

        return $output;
    }

    public function deserializeFromArray($data)
    {
        foreach ($data as $key => $value) {
            if (in_array($key, $this->serializable_object_transient_properties)) {
                continue;
            }

            if (property_exists($this, $key)) {
                if (array_key_exists($key, $this->serializable_object_registered_properties)) {
                    $registered_type = $this->serializable_object_registered_properties[$key];
                    if ($registered_type->getIsArray() === false) {
                        $this->$key = self::create($value, $registered_type->getClassName());
                    } else {
                        $this->$key = [];
                        foreach ($value as $object_key => $object_value) {
                            $this->$key[$object_key] = self::create($object_value, $registered_type->getClassName());
                        }
                    }
                } else {
                    $this->$key = $value;
                }
            }
        }

        $this->validate();
    }

    #region Static Functions

    public static function create($data, $class_name)
    {
        if ($data == null) {
            return null;
        }
        if (!is_array($data)) {
            throw new \InvalidArgumentException('data must be an array');
        }

        if (!class_exists($class_name)) {
            throw new \InvalidArgumentException("class_name {$class_name} cannot found on namespace");
        }

        $output = new $class_name();

        if ($output instanceof SerializableObject) {
            $output->deserializeFromArray($data);
        }

        return $output;
    }

    public static function deserialize($data, $is_array = false)
    {
        if ($is_array) {
            $output = [];
            foreach ($data as $key => $sub_data) {
                $output[$key] = static::deserialize($sub_data);
            }
            return $output;
        } else {
            $class_name = static::class;
            return self::create($data, $class_name);
        }
    }

    #endregion

    #region Protected Functions

    protected function makeTransient($property_name)
    {
        if (!in_array($property_name, $this->serializable_object_transient_properties)) {
            $this->serializable_object_transient_properties[] = $property_name;
        }
        return $this;
    }

    protected function registerProperty($property_name, $class_name, $is_array = false)
    {
        $this->serializable_object_registered_properties[$property_name]
            = new RegisteredProperty($class_name, $is_array);

        $validation = $this->addObjectValidation($property_name)->setType($class_name);

        if ($is_array) {
            $validation->isArray();
        }

        return $validation;
    }

    #region Validations
    /**
     * @param $property_name
     * @return IntegerValidation
     */
    protected function addIntegerValidation($property_name)
    {
        $validation = new IntegerValidation();
        $this->addValidation($property_name, $validation);
        return $validation;
    }

    /**
     * @param $property_name string
     * @return StringValidation
     */
    protected function addStringValidation($property_name)
    {
        $validation = new StringValidation();
        $this->addValidation($property_name, $validation);
        return $validation;
    }

    /**
     * @param $property_name string
     * @return FloatValidation
     */
    protected function addFloatValidation($property_name)
    {
        $validation = new FloatValidation();
        $this->addValidation($property_name, $validation);
        return $validation;
    }

    /**
     * @param $property_name string
     * @return ObjectValidation
     */
    protected function addObjectValidation($property_name)
    {
        $validation = new ObjectValidation();
        $this->addValidation($property_name, $validation);
        return $validation;
    }

    /**
     * @param $property_name string
     * @return RenderIfNotNullCondition
     */
    protected function addRenderIfNotNullCondition($property_name)
    {
        $condition = new RenderIfNotNullCondition();
        $this->addCondition($property_name, $condition);
        return $condition;
    }

    /**
     * @param $property_name string
     * @return ClampCondition
     */
    protected function addClampCondition($property_name)
    {
        $condition = new ClampCondition();
        $this->addCondition($property_name, $condition);
        return $condition;
    }

    /**
     * @param $property_name
     * @return BooleanValidation
     */
    protected function addBooleanValidation($property_name)
    {
        $validation = new BooleanValidation();
        $this->addValidation($property_name, $validation);
        return $validation;
    }

    /**
     * @param string $property_name
     * @param ConditionBase $condition
     * @return null|ConditionBase
     */
    protected function addCondition($property_name, $condition)
    {
        if ($condition instanceof ConditionBase) {

            if (!array_key_exists($property_name, $this->serializable_object_conditions)) {
                $this->serializable_object_conditions[$property_name] = [];
            }

            $this->serializable_object_conditions[$property_name][] = $condition;
            return $condition;
        }
        return null;
    }

    /**
     * @param string $property_name
     * @param ValidationBase $validation
     * @return null|ValidationBase
     */
    protected function addValidation($property_name, $validation)
    {
        if ($validation instanceof ValidationBase) {

            if (!array_key_exists($property_name, $this->serializable_object_validations)) {
                $this->serializable_object_validations[$property_name] = [];
            }

            $this->serializable_object_validations[$property_name][] = $validation;
            return $validation;
        }
        return null;
    }

    protected function validate()
    {
        foreach ($this as $key => $value) {
            if (array_key_exists($key, $this->serializable_object_validations)) {

                foreach ($this->serializable_object_validations[$key] as $validation) {
                    $validation_failed = false;

                    if ($validation->getIsArray()) {
                        if (!is_array($value)) {
                            $validation_failed = true;
                        } else {
                            foreach ($value as $value2) {
                                if (!$validation->isValid($value2)) {
                                    $validation_failed = true;
                                    break;
                                }
                            }
                        }
                    } else if (!$validation->isValid($value)) {
                        $validation_failed = true;
                    }

                    if ($validation_failed) {
                        throw new ValidationException(get_class($this), $key, $validation->getValidationName());
                    }
                }
            }
        }
    }

    protected function applyConditions($property_name, &$value)
    {
        if (array_key_exists($property_name, $this->serializable_object_conditions)) {
            foreach ($this->serializable_object_conditions[$property_name] as $condition) {
                $condition->apply($value);

                if (!$condition->getShouldRender()) {
                    return false;
                }

                if ($condition->getShouldValueUpdate()) {
                    $value = $condition->getNewValue();
                }
            }
        }

        return true;
    }

    #endregion

    #endregion
}
<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Reflection;

use ReflectionClass as NativeReflectionClass;
use ReflectionParameter as NativeReflectionParameter;
use ReflectionType;
use ReflectionFunctionAbstract;

/**
 * Class ReflectionParameter
 * @author mfris
 * @package BetterSerializer\Reflection
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
final class ReflectionParameter implements ReflectionParameterInterface
{

    /**
     * @var NativeReflectionParameter
     */
    private $nativeReflParameter;

    /**
     * @var ReflectionClassInterface
     */
    private $declaringClass;

    /**
     * ReflectionParameter constructor.
     * @param NativeReflectionParameter $nativeReflParameter
     * @param ReflectionClassInterface $declaringClass
     */
    public function __construct(
        NativeReflectionParameter $nativeReflParameter,
        ReflectionClassInterface $declaringClass
    ) {
        $this->nativeReflParameter = $nativeReflParameter;
        $this->declaringClass = $declaringClass;
    }

    /**
     * @return NativeReflectionParameter
     */
    public function getNativeReflParameter(): NativeReflectionParameter
    {
        return $this->nativeReflParameter;
    }

    /**
     * Gets parameter name
     * @link http://php.net/manual/en/reflectionparameter.getname.php
     * @return string The name of the reflected parameter.
     * @since 5.0
     */
    public function getName(): string
    {
        return $this->nativeReflParameter->getName();
    }

    /**
     * Gets a parameter's type
     * @link http://php.net/manual/en/reflectionparameter.gettype.php
     * @return ReflectionType|NULL Returns a ReflectionType object if a parameter type is specified, NULL otherwise.
     * @since 7.0
     */
    public function getType(): ?ReflectionType
    {
        return $this->nativeReflParameter->getType();
    }

    /**
     * Checks if the parameter has a type associated with it.
     * @link http://php.net/manual/en/reflectionparameter.hastype.php
     * @return bool TRUE if a type is specified, FALSE otherwise.
     * @since 7.0
     */
    public function hasType(): bool
    {
        return $this->nativeReflParameter->hasType();
    }


    /**
     * Checks if passed by reference
     * @link http://php.net/manual/en/reflectionparameter.ispassedbyreference.php
     * @return bool <b>TRUE</b> if the parameter is passed in by reference, otherwise <b>FALSE</b>
     * @since 5.0
     */
    public function isPassedByReference(): bool
    {
        return $this->nativeReflParameter->isPassedByReference();
    }

    /**
     * Returns whether this parameter can be passed by value
     * @link http://php.net/manual/en/reflectionparameter.canbepassedbyvalue.php
     * @return bool <b>TRUE</b> if the parameter can be passed by value, <b>FALSE</b> otherwise.
     * Returns <b>NULL</b> in case of an error.
     * @since 5.4.0
     */
    public function canBePassedByValue(): bool
    {
        return $this->nativeReflParameter->canBePassedByValue();
    }

    /**
     * Gets declaring function
     * @link http://php.net/manual/en/reflectionparameter.getdeclaringfunction.php
     * @return ReflectionFunctionAbstract A <b>ReflectionFunctionAbstract</b> object.
     * @since 5.2.3
     */
    public function getDeclaringFunction(): ReflectionFunctionAbstract
    {
        return $this->nativeReflParameter->getDeclaringFunction();
    }

    /**
     * Gets declaring class
     * @link http://php.net/manual/en/reflectionparameter.getdeclaringclass.php
     * @return ReflectionClassInterface A <b>ReflectionClass</b> object.
     * @since 5.0
     */
    public function getDeclaringClass(): ReflectionClassInterface
    {
        return $this->declaringClass;
    }

    /**
     * Get class
     * @link http://php.net/manual/en/reflectionparameter.getclass.php
     * @return NativeReflectionClass A <b>ReflectionClass</b> object.
     * @since 5.0
     */
    public function getClass(): NativeReflectionClass
    {
        return $this->nativeReflParameter->getClass();
    }

    /**
     * Checks if parameter expects an array
     * @link http://php.net/manual/en/reflectionparameter.isarray.php
     * @return bool <b>TRUE</b> if an array is expected, <b>FALSE</b> otherwise.
     * @since 5.1.0
     */
    public function isArray(): bool
    {
        return $this->nativeReflParameter->isArray();
    }

    /**
     * Returns whether parameter MUST be callable
     * @link http://php.net/manual/en/reflectionparameter.iscallable.php
     * @return bool Returns TRUE if the parameter is callable, FALSE if it is not or NULL on failure.
     * @since 5.4.0
     */
    public function isCallable(): bool
    {
        return $this->nativeReflParameter->isCallable();
    }

    /**
     * Checks if null is allowed
     * @link http://php.net/manual/en/reflectionparameter.allowsnull.php
     * @return bool <b>TRUE</b> if <b>NULL</b> is allowed, otherwise <b>FALSE</b>
     * @since 5.0
     */
    public function allowsNull(): bool
    {
        return $this->nativeReflParameter->allowsNull();
    }

    /**
     * Gets parameter position
     * @link http://php.net/manual/en/reflectionparameter.getposition.php
     * @return int The position of the parameter, left to right, starting at position #0.
     * @since 5.2.3
     */
    public function getPosition(): int
    {
        return $this->nativeReflParameter->getPosition();
    }

    /**
     * Checks if optional
     * @link http://php.net/manual/en/reflectionparameter.isoptional.php
     * @return bool <b>TRUE</b> if the parameter is optional, otherwise <b>FALSE</b>
     * @since 5.0.3
     */
    public function isOptional(): bool
    {
        return $this->nativeReflParameter->isOptional();
    }

    /**
     * Checks if a default value is available
     * @link http://php.net/manual/en/reflectionparameter.isdefaultvalueavailable.php
     * @return bool <b>TRUE</b> if a default value is available, otherwise <b>FALSE</b>
     * @since 5.0.3
     */
    public function isDefaultValueAvailable(): bool
    {
        return $this->nativeReflParameter->isDefaultValueAvailable();
    }

    /**
     * Gets default parameter value
     * @link http://php.net/manual/en/reflectionparameter.getdefaultvalue.php
     * @return mixed The parameters default value.
     * @since 5.0.3
     */
    public function getDefaultValue()
    {
        return $this->nativeReflParameter->getDefaultValue();
    }

    /**
     * Returns whether the default value of this parameter is constant
     * @return boolean
     * @since 5.4.6
     */
    public function isDefaultValueConstant(): bool
    {
        return $this->nativeReflParameter->isDefaultValueConstant();
    }

    /**
     * Returns the default value's constant name if default value is constant or null
     * @return string
     * @since 5.4.6
     */
    public function getDefaultValueConstantName(): string
    {
        return $this->nativeReflParameter->getDefaultValueConstantName();
    }

    /**
     * Returns whether this function is variadic
     * @link http://php.net/manual/en/reflectionparameter.isvariadic.php
     * @return bool <b>TRUE</b> if the function is variadic, otherwise <b>FALSE</b>
     * @since 5.6.0
     */
    public function isVariadic(): bool
    {
        return $this->nativeReflParameter->isVariadic();
    }
}

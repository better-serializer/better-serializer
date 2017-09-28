<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Reflection;

use ReflectionProperty as NativeReflectionProperty;
use ReflectionException;

/**
 * Class ReflectionProperty
 * @author mfris
 * @package BetterSerializer\Reflection
 */
final class ReflectionProperty implements ReflectionPropertyInterface
{

    /**
     * @var NativeReflectionProperty
     */
    private $nativeReflProperty;

    /**
     * @var ReflectionClassInterface
     */
    private $declaringClass;

    /**
     * @var string
     */
    private $propertyName;

    /**
     * ReflectionProperty constructor.
     * @param NativeReflectionProperty $nativeReflProperty
     * @param ReflectionClassInterface $declaringClass
     */
    public function __construct(NativeReflectionProperty $nativeReflProperty, ReflectionClassInterface $declaringClass)
    {
        $this->nativeReflProperty = $nativeReflProperty;
        $this->nativeReflProperty->setAccessible(true);
        $this->declaringClass = $declaringClass;
        $this->propertyName = $nativeReflProperty->getName();
    }

    /**
     * @return NativeReflectionProperty
     * @throws ReflectionException
     */
    public function getNativeReflProperty(): NativeReflectionProperty
    {
        if ($this->nativeReflProperty === null) {
            $this->nativeReflProperty =
                new NativeReflectionProperty($this->declaringClass->getName(), $this->propertyName);
            $this->nativeReflProperty->setAccessible(true);
        }

        return $this->nativeReflProperty;
    }

    /**
     * Gets property name
     * @link http://php.net/manual/en/reflectionproperty.getname.php
     * @return string The name of the reflected property.
     * @since 5.0
     * @throws ReflectionException
     */
    public function getName(): string
    {
        return $this->getNativeReflProperty()->getName();
    }

    /**
     * Gets value
     * @link http://php.net/manual/en/reflectionproperty.getvalue.php
     * @param object $object [optional]<p>
     * If the property is non-static an object must be provided to fetch the
     * property from. If you want to fetch the default property without
     * providing an object use <b>ReflectionClass::getDefaultProperties</b>
     * instead.
     * </p>
     * @return mixed The current value of the property.
     * @since 5.0
     * @throws ReflectionException
     */
    public function getValue($object)
    {
        return $this->getNativeReflProperty()->getValue($object);
    }

    /**
     * Set property value
     * @link http://php.net/manual/en/reflectionproperty.setvalue.php
     * @param object $object [optional]<p>
     * If the property is non-static an object must be provided to change
     * the property on. If the property is static this parameter is left
     * out and only <i>value</i> needs to be provided.
     * </p>
     * @param mixed $value <p>
     * The new value.
     * </p>
     * @return void No value is returned.
     * @since 5.0
     * @throws ReflectionException
     */
    public function setValue($object, $value): void
    {
        $this->getNativeReflProperty()->setValue($object, $value);
    }

    /**
     * Checks if property is public
     * @link http://php.net/manual/en/reflectionproperty.ispublic.php
     * @return bool <b>TRUE</b> if the property is public, <b>FALSE</b> otherwise.
     * @since 5.0
     * @throws ReflectionException
     */
    public function isPublic(): bool
    {
        return $this->getNativeReflProperty()->isPublic();
    }

    /**
     * Checks if property is private
     * @link http://php.net/manual/en/reflectionproperty.isprivate.php
     * @return bool <b>TRUE</b> if the property is private, <b>FALSE</b> otherwise.
     * @since 5.0
     * @throws ReflectionException
     */
    public function isPrivate(): bool
    {
        return $this->getNativeReflProperty()->isPrivate();
    }

    /**
     * Checks if property is protected
     * @link http://php.net/manual/en/reflectionproperty.isprotected.php
     * @return bool <b>TRUE</b> if the property is protected, <b>FALSE</b> otherwise.
     * @since 5.0
     * @throws ReflectionException
     */
    public function isProtected(): bool
    {
        return $this->getNativeReflProperty()->isProtected();
    }

    /**
     * Checks if property is static
     * @link http://php.net/manual/en/reflectionproperty.isstatic.php
     * @return bool <b>TRUE</b> if the property is static, <b>FALSE</b> otherwise.
     * @since 5.0
     * @throws ReflectionException
     */
    public function isStatic(): bool
    {
        return $this->getNativeReflProperty()->isStatic();
    }

    /**
     * Checks if default value
     * @link http://php.net/manual/en/reflectionproperty.isdefault.php
     * @return bool <b>TRUE</b> if the property was declared at compile-time, or <b>FALSE</b> if
     * it was created at run-time.
     * @since 5.0
     * @throws ReflectionException
     */
    public function isDefault(): bool
    {
        return $this->getNativeReflProperty()->isDefault();
    }

    /**
     * Gets modifiers
     * @link http://php.net/manual/en/reflectionproperty.getmodifiers.php
     * @return int A numeric representation of the modifiers.
     * @since 5.0
     * @throws ReflectionException
     */
    public function getModifiers(): int
    {
        return $this->getNativeReflProperty()->getModifiers();
    }

    /**
     * Gets declaring class
     * @link http://php.net/manual/en/reflectionproperty.getdeclaringclass.php
     * @return ReflectionClassInterface A <b>ReflectionClass</b> object.
     * @since 5.0
     * @throws ReflectionException
     */
    public function getDeclaringClass(): ReflectionClassInterface
    {
        return $this->declaringClass;
    }

    /**
     * Gets doc comment
     * @link http://php.net/manual/en/reflectionproperty.getdoccomment.php
     * @return string The doc comment.
     * @since 5.1.0
     * @throws ReflectionException
     */
    public function getDocComment(): string
    {
        return $this->getNativeReflProperty()->getDocComment();
    }

    /**
     * Set property accessibility
     * @link http://php.net/manual/en/reflectionproperty.setaccessible.php
     * @param bool $accessible <p>
     * <b>TRUE</b> to allow accessibility, or <b>FALSE</b>.
     * </p>
     * @return void No value is returned.
     * @since 5.3.0
     * @throws ReflectionException
     */
    public function setAccessible(bool $accessible): void
    {
        $this->getNativeReflProperty()->setAccessible($accessible);
    }

    /**
     *
     */
    public function __sleep()
    {
        return [
            'declaringClass',
            'propertyName',
        ];
    }

    /**
     * @throws ReflectionException
     */
    public function __wakeup()
    {
        $this->getNativeReflProperty();
    }
}

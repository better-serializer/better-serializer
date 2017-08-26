<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Reflection;

use ReflectionProperty as NativeReflectionProperty;

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
     * ReflectionProperty constructor.
     * @param NativeReflectionProperty $nativeReflProperty
     * @param ReflectionClassInterface $declaringClass
     */
    public function __construct(NativeReflectionProperty $nativeReflProperty, ReflectionClassInterface $declaringClass)
    {
        $this->nativeReflProperty = $nativeReflProperty;
        $this->declaringClass = $declaringClass;
    }

    /**
     * @return NativeReflectionProperty
     */
    public function getNativeReflProperty(): NativeReflectionProperty
    {
        return $this->nativeReflProperty;
    }

    /**
     * Gets property name
     * @link http://php.net/manual/en/reflectionproperty.getname.php
     * @return string The name of the reflected property.
     * @since 5.0
     */
    public function getName(): string
    {
        return $this->nativeReflProperty->getName();
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
     */
    public function getValue($object)
    {
        return $this->nativeReflProperty->getValue($object);
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
     */
    public function setValue($object, $value): void
    {
        $this->nativeReflProperty->setValue($object, $value);
    }

    /**
     * Checks if property is public
     * @link http://php.net/manual/en/reflectionproperty.ispublic.php
     * @return bool <b>TRUE</b> if the property is public, <b>FALSE</b> otherwise.
     * @since 5.0
     */
    public function isPublic(): bool
    {
        return $this->nativeReflProperty->isPublic();
    }

    /**
     * Checks if property is private
     * @link http://php.net/manual/en/reflectionproperty.isprivate.php
     * @return bool <b>TRUE</b> if the property is private, <b>FALSE</b> otherwise.
     * @since 5.0
     */
    public function isPrivate(): bool
    {
        return $this->nativeReflProperty->isPrivate();
    }

    /**
     * Checks if property is protected
     * @link http://php.net/manual/en/reflectionproperty.isprotected.php
     * @return bool <b>TRUE</b> if the property is protected, <b>FALSE</b> otherwise.
     * @since 5.0
     */
    public function isProtected(): bool
    {
        return $this->nativeReflProperty->isProtected();
    }

    /**
     * Checks if property is static
     * @link http://php.net/manual/en/reflectionproperty.isstatic.php
     * @return bool <b>TRUE</b> if the property is static, <b>FALSE</b> otherwise.
     * @since 5.0
     */
    public function isStatic(): bool
    {
        return $this->nativeReflProperty->isStatic();
    }

    /**
     * Checks if default value
     * @link http://php.net/manual/en/reflectionproperty.isdefault.php
     * @return bool <b>TRUE</b> if the property was declared at compile-time, or <b>FALSE</b> if
     * it was created at run-time.
     * @since 5.0
     */
    public function isDefault(): bool
    {
        return $this->nativeReflProperty->isDefault();
    }

    /**
     * Gets modifiers
     * @link http://php.net/manual/en/reflectionproperty.getmodifiers.php
     * @return int A numeric representation of the modifiers.
     * @since 5.0
     */
    public function getModifiers(): int
    {
        return $this->nativeReflProperty->getModifiers();
    }

    /**
     * Gets declaring class
     * @link http://php.net/manual/en/reflectionproperty.getdeclaringclass.php
     * @return ReflectionClassInterface A <b>ReflectionClass</b> object.
     * @since 5.0
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
     */
    public function getDocComment(): string
    {
        return $this->nativeReflProperty->getDocComment();
    }

    /**
     * Set property accessibility
     * @link http://php.net/manual/en/reflectionproperty.setaccessible.php
     * @param bool $accessible <p>
     * <b>TRUE</b> to allow accessibility, or <b>FALSE</b>.
     * </p>
     * @return void No value is returned.
     * @since 5.3.0
     */
    public function setAccessible(bool $accessible): void
    {
        $this->nativeReflProperty->setAccessible($accessible);
    }
}

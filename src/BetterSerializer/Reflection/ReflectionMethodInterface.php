<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Reflection;

use Closure;
use ReflectionMethod as NativeReflectionMethod;

/**
 * Class ReflectionMethod
 * @author mfris
 * @package BetterSerializer\Reflection
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
interface ReflectionMethodInterface extends AbstractReflectionFunctionInterface
{
    /**
     * @return NativeReflectionMethod
     */
    public function getNativeReflMethod(): NativeReflectionMethod;

    /**
     * Checks if method is public
     * @link http://php.net/manual/en/reflectionmethod.ispublic.php
     * @return bool <b>TRUE</b> if the method is public, otherwise <b>FALSE</b>
     * @since 5.0
     */
    public function isPublic(): bool;

    /**
     * Checks if method is private
     * @link http://php.net/manual/en/reflectionmethod.isprivate.php
     * @return bool <b>TRUE</b> if the method is private, otherwise <b>FALSE</b>
     * @since 5.0
     */
    public function isPrivate(): bool;

    /**
     * Checks if method is protected
     * @link http://php.net/manual/en/reflectionmethod.isprotected.php
     * @return bool <b>TRUE</b> if the method is protected, otherwise <b>FALSE</b>
     * @since 5.0
     */
    public function isProtected(): bool;

    /**
     * Checks if method is abstract
     * @link http://php.net/manual/en/reflectionmethod.isabstract.php
     * @return bool <b>TRUE</b> if the method is abstract, otherwise <b>FALSE</b>
     * @since 5.0
     */
    public function isAbstract(): bool;

    /**
     * Checks if method is final
     * @link http://php.net/manual/en/reflectionmethod.isfinal.php
     * @return bool <b>TRUE</b> if the method is final, otherwise <b>FALSE</b>
     * @since 5.0
     */
    public function isFinal(): bool;

    /**
     * Checks if method is static
     * @link http://php.net/manual/en/reflectionmethod.isstatic.php
     * @return bool <b>TRUE</b> if the method is static, otherwise <b>FALSE</b>
     * @since 5.0
     */
    public function isStatic(): bool;

    /**
     * Checks if method is a constructor
     * @link http://php.net/manual/en/reflectionmethod.isconstructor.php
     * @return bool <b>TRUE</b> if the method is a constructor, otherwise <b>FALSE</b>
     * @since 5.0
     */
    public function isConstructor(): bool;

    /**
     * Checks if method is a destructor
     * @link http://php.net/manual/en/reflectionmethod.isdestructor.php
     * @return bool <b>TRUE</b> if the method is a destructor, otherwise <b>FALSE</b>
     * @since 5.0
     */
    public function isDestructor(): bool;

    /**
     * Returns a dynamically created closure for the method
     * @link http://php.net/manual/en/reflectionmethod.getclosure.php
     * @param object $object [optional] Forbidden for static methods, required for other methods.
     * @return Closure <b>Closure</b>.
     * Returns <b>NULL</b> in case of an error.
     * @since 5.4.0
     */
    public function getClosure($object): Closure;

    /**
     * Gets the method modifiers
     * @link http://php.net/manual/en/reflectionmethod.getmodifiers.php
     * @return int A numeric representation of the modifiers. The modifiers are listed below.
     * The actual meanings of these modifiers are described in the
     * predefined constants.
     * <table>
     * ReflectionMethod modifiers
     * <tr valign="top">
     * <td>value</td>
     * <td>constant</td>
     * </tr>
     * <tr valign="top">
     * <td>1</td>
     * <td>
     * ReflectionMethod::IS_STATIC
     * </td>
     * </tr>
     * <tr valign="top">
     * <td>2</td>
     * <td>
     * ReflectionMethod::IS_ABSTRACT
     * </td>
     * </tr>
     * <tr valign="top">
     * <td>4</td>
     * <td>
     * ReflectionMethod::IS_FINAL
     * </td>
     * </tr>
     * <tr valign="top">
     * <td>256</td>
     * <td>
     * ReflectionMethod::IS_PUBLIC
     * </td>
     * </tr>
     * <tr valign="top">
     * <td>512</td>
     * <td>
     * ReflectionMethod::IS_PROTECTED
     * </td>
     * </tr>
     * <tr valign="top">
     * <td>1024</td>
     * <td>
     * ReflectionMethod::IS_PRIVATE
     * </td>
     * </tr>
     * </table>
     * @since 5.0
     */
    public function getModifiers(): int;

    /**
     * Invoke
     * @link http://php.net/manual/en/reflectionmethod.invoke.php
     * @param object $object <p>
     * The object to invoke the method on. For static methods, pass
     * null to this parameter.
     * </p>
     * @param mixed $parameter [optional] <p>
     * Zero or more parameters to be passed to the method.
     * It accepts a variable number of parameters which are passed to the method.
     * </p>
     * @param mixed $optional [optional]
     * @return mixed the method result.
     * @since 5.0
     */
    public function invoke($object, $parameter = null, $optional = null);

    /**
     * Invoke args
     * @link http://php.net/manual/en/reflectionmethod.invokeargs.php
     * @param object $object <p>
     * The object to invoke the method on. In case of static methods, you can pass
     * null to this parameter.
     * </p>
     * @param array $args <p>
     * The parameters to be passed to the function, as an array.
     * </p>
     * @return mixed the method result.
     * @since 5.1.0
     */
    public function invokeArgs($object, array $args);

    /**
     * Gets declaring class for the reflected method.
     * @link http://php.net/manual/en/reflectionmethod.getdeclaringclass.php
     * @return ReflectionClassInterface A <b>ReflectionClass</b> object of the class that the
     * reflected method is part of.
     * @since 5.0
     */
    public function getDeclaringClass(): ReflectionClassInterface;

    /**
     * Gets the method prototype (if there is one).
     * @link http://php.net/manual/en/reflectionmethod.getprototype.php
     * @return NativeReflectionMethod A <b>ReflectionMethod</b> instance of the method prototype.
     * @since 5.0
     */
    public function getPrototype(): NativeReflectionMethod;

    /**
     * Set method accessibility
     * @link http://php.net/manual/en/reflectionmethod.setaccessible.php
     * @param bool $accessible <p>
     * <b>TRUE</b> to allow accessibility, or <b>FALSE</b>.
     * </p>
     * @return void No value is returned.
     * @since 5.3.2
     */
    public function setAccessible($accessible): void;
}

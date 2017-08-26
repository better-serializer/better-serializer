<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Reflection;

use ReflectionExtension;
use ReflectionMethod as NativeReflectionMethod;
use Closure;
use ReflectionType;

/**
 * Class ReflectionMethod
 * @author mfris
 * @package BetterSerializer\Reflection
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
final class ReflectionMethod implements ReflectionMethodInterface
{

    /**
     * @var NativeReflectionMethod
     */
    private $nativeReflMethod;

    /**
     * @var ReflectionClassInterface
     */
    private $declaringClass;

    /**
     * @var ReflectionParameterInterface[]
     */
    private $parameters;

    /**
     * ReflectionMethod constructor.
     * @param NativeReflectionMethod $nativeReflMethod
     * @param ReflectionClassInterface $declaringClass
     */
    public function __construct(NativeReflectionMethod $nativeReflMethod, ReflectionClassInterface $declaringClass)
    {
        $this->nativeReflMethod = $nativeReflMethod;
        $this->declaringClass = $declaringClass;
    }

    /**
     * @return NativeReflectionMethod
     */
    public function getNativeReflMethod(): NativeReflectionMethod
    {
        return $this->nativeReflMethod;
    }

    /**
     * Checks if method is public
     * @link http://php.net/manual/en/reflectionmethod.ispublic.php
     * @return bool <b>TRUE</b> if the method is public, otherwise <b>FALSE</b>
     * @since 5.0
     */
    public function isPublic(): bool
    {
        return $this->nativeReflMethod->isPublic();
    }

    /**
     * Checks if method is private
     * @link http://php.net/manual/en/reflectionmethod.isprivate.php
     * @return bool <b>TRUE</b> if the method is private, otherwise <b>FALSE</b>
     * @since 5.0
     */
    public function isPrivate(): bool
    {
        return $this->nativeReflMethod->isPrivate();
    }

    /**
     * Checks if method is protected
     * @link http://php.net/manual/en/reflectionmethod.isprotected.php
     * @return bool <b>TRUE</b> if the method is protected, otherwise <b>FALSE</b>
     * @since 5.0
     */
    public function isProtected(): bool
    {
        return $this->nativeReflMethod->isProtected();
    }

    /**
     * Checks if method is abstract
     * @link http://php.net/manual/en/reflectionmethod.isabstract.php
     * @return bool <b>TRUE</b> if the method is abstract, otherwise <b>FALSE</b>
     * @since 5.0
     */
    public function isAbstract(): bool
    {
        return $this->nativeReflMethod->isAbstract();
    }

    /**
     * Checks if method is final
     * @link http://php.net/manual/en/reflectionmethod.isfinal.php
     * @return bool <b>TRUE</b> if the method is final, otherwise <b>FALSE</b>
     * @since 5.0
     */
    public function isFinal(): bool
    {
        return $this->nativeReflMethod->isFinal();
    }

    /**
     * Checks if method is static
     * @link http://php.net/manual/en/reflectionmethod.isstatic.php
     * @return bool <b>TRUE</b> if the method is static, otherwise <b>FALSE</b>
     * @since 5.0
     */
    public function isStatic(): bool
    {
        return $this->nativeReflMethod->isStatic();
    }

    /**
     * Checks if method is a constructor
     * @link http://php.net/manual/en/reflectionmethod.isconstructor.php
     * @return bool <b>TRUE</b> if the method is a constructor, otherwise <b>FALSE</b>
     * @since 5.0
     */
    public function isConstructor(): bool
    {
        return $this->nativeReflMethod->isConstructor();
    }

    /**
     * Checks if method is a destructor
     * @link http://php.net/manual/en/reflectionmethod.isdestructor.php
     * @return bool <b>TRUE</b> if the method is a destructor, otherwise <b>FALSE</b>
     * @since 5.0
     */
    public function isDestructor(): bool
    {
        return $this->nativeReflMethod->isDestructor();
    }

    /**
     * Returns a dynamically created closure for the method
     * @link http://php.net/manual/en/reflectionmethod.getclosure.php
     * @param object $object [optional] Forbidden for static methods, required for other methods.
     * @return Closure <b>Closure</b>.
     * Returns <b>NULL</b> in case of an error.
     * @since 5.4.0
     */
    public function getClosure($object): Closure
    {
        return $this->nativeReflMethod->getClosure($object);
    }

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
    public function getModifiers(): int
    {
        return $this->nativeReflMethod->getModifiers();
    }

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
    public function invoke($object, $parameter = null, $optional = null)
    {
        return $this->nativeReflMethod->invoke($object, $parameter, $optional);
    }

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
    public function invokeArgs($object, array $args)
    {
        return $this->nativeReflMethod->invokeArgs($object, $args);
    }

    /**
     * Gets declaring class for the reflected method.
     * @link http://php.net/manual/en/reflectionmethod.getdeclaringclass.php
     * @return ReflectionClassInterface A <b>ReflectionClass</b> object of the class that the
     * reflected method is part of.
     * @since 5.0
     */
    public function getDeclaringClass(): ReflectionClassInterface
    {
        return $this->declaringClass;
    }

    /**
     * Gets the method prototype (if there is one).
     * @link http://php.net/manual/en/reflectionmethod.getprototype.php
     * @return NativeReflectionMethod A <b>ReflectionMethod</b> instance of the method prototype.
     * @since 5.0
     */
    public function getPrototype(): NativeReflectionMethod
    {
        return $this->nativeReflMethod->getPrototype();
    }

    /**
     * Set method accessibility
     * @link http://php.net/manual/en/reflectionmethod.setaccessible.php
     * @param bool $accessible <p>
     * <b>TRUE</b> to allow accessibility, or <b>FALSE</b>.
     * </p>
     * @return void No value is returned.
     * @since 5.3.2
     */
    public function setAccessible($accessible): void
    {
        $this->nativeReflMethod->setAccessible($accessible);
    }

    /**
     * @inheritdoc
     * @return bool
     */
    public function inNamespace(): bool
    {
        return $this->nativeReflMethod->inNamespace();
    }

    /**
     * @inheritdoc
     * @return bool
     */
    public function isClosure(): bool
    {
        return $this->nativeReflMethod->isClosure();
    }

    /**
     * @inheritdoc
     * @return bool
     */
    public function isDeprecated(): bool
    {
        return $this->nativeReflMethod->isDeprecated();
    }

    /**
     * @inheritdoc
     * @return bool
     */
    public function isInternal(): bool
    {
        return $this->nativeReflMethod->isInternal();
    }

    /**
     * @inheritdoc
     * @return bool
     */
    public function isUserDefined(): bool
    {
        return $this->nativeReflMethod->isUserDefined();
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function getDocComment(): string
    {
        return $this->nativeReflMethod->getDocComment();
    }

    /**
     * @inheritdoc
     * @return int
     */
    public function getEndLine(): int
    {
        return $this->nativeReflMethod->getEndLine();
    }

    /**
     * @inheritdoc
     * @return ReflectionExtension
     */
    public function getExtension(): ReflectionExtension
    {
        return $this->nativeReflMethod->getExtension();
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function getExtensionName(): string
    {
        return $this->nativeReflMethod->getExtensionName();
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function getFileName(): string
    {
        return $this->nativeReflMethod->getFileName();
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function getName(): string
    {
        return $this->nativeReflMethod->getName();
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function getNamespaceName(): string
    {
        return $this->nativeReflMethod->getNamespaceName();
    }

    /**
     * @inheritdoc
     * @return int
     */
    public function getNumberOfParameters(): int
    {
        return $this->nativeReflMethod->getNumberOfParameters();
    }

    /**
     * @inheritdoc
     * @return int
     */
    public function getNumberOfRequiredParameters(): int
    {
        return $this->nativeReflMethod->getNumberOfRequiredParameters();
    }

    /**
     * @inheritdoc
     * @return ReflectionParameterInterface[]
     */
    public function getParameters(): array
    {
        if ($this->parameters !== null) {
            return $this->parameters;
        }

        $this->parameters = [];

        foreach ($this->nativeReflMethod->getParameters() as $parameter) {
            $this->parameters[] = new ReflectionParameter($parameter, $this->declaringClass);
        }

        return $this->parameters;
    }

    /**
     * @inheritdoc
     * @return null|ReflectionType
     */
    public function getReturnType(): ?ReflectionType
    {
        return $this->nativeReflMethod->getReturnType();
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function getShortName(): string
    {
        return $this->nativeReflMethod->getShortName();
    }

    /**
     * @inheritdoc
     * @return int
     */
    public function getStartLine(): int
    {
        return $this->nativeReflMethod->getStartLine();
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function getStaticVariables(): array
    {
        return $this->nativeReflMethod->getStaticVariables();
    }

    /**
     * @inheritdoc
     * @return bool
     */
    public function hasReturnType(): bool
    {
        return $this->nativeReflMethod->hasReturnType();
    }

    /**
     * @inheritdoc
     * @return bool
     */
    public function returnsReference(): bool
    {
        return $this->nativeReflMethod->returnsReference();
    }

    /**
     * @inheritdoc
     * @return bool
     */
    public function isGenerator(): bool
    {
        return $this->nativeReflMethod->isGenerator();
    }

    /**
     * @inheritdoc
     * @return bool
     */
    public function isVariadic(): bool
    {
        return $this->nativeReflMethod->isVariadic();
    }
}

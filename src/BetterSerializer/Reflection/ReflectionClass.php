<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Reflection;

use BetterSerializer\Reflection\UseStatement\UseStatementsInterface;
use ReflectionClass as NativeReflectionClass;
use ReflectionExtension;
use ReflectionException;
use RuntimeException;

/**
 * Class ReflectionClass
 * @author mfris
 * @package BetterSerializer\Reflection
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
final class ReflectionClass implements ReflectionClassInterface
{

    /**
     * @var NativeReflectionClass
     */
    private $nativeReflClass;

    /**
     * @var ReflectionClass|null
     */
    private $parentClass;

    /**
     * @var ReflectionProperty[]
     */
    private $properties;

    /**
     * @var ReflectionMethod[]
     */
    private $methods;

    /**
     * @var ReflectionMethod|null|string
     */
    private $constructor = '';

    /**
     * @var ReflectionProperty[]
     */
    private $indexedProperties;

    /**
     * @var ReflectionMethod[]
     */
    private $indexedMethods;

    /**
     * Array of use statements for class.
     *
     * @var UseStatementsInterface
     */
    private $useStatements;

    /**
     * ReflectionClass constructor.
     * @param NativeReflectionClass $nativeReflClass
     * @param UseStatementsInterface $useStatements
     * @param ReflectionClassInterface $parentClass
     */
    public function __construct(
        NativeReflectionClass $nativeReflClass,
        UseStatementsInterface $useStatements,
        ReflectionClassInterface $parentClass = null
    ) {
        $this->nativeReflClass = $nativeReflClass;
        $this->useStatements = $useStatements;
        $this->parentClass = $parentClass;
    }

    /**
     * @return NativeReflectionClass
     */
    public function getNativeReflClass(): NativeReflectionClass
    {
        return $this->nativeReflClass;
    }

    /**
     * @return UseStatementsInterface
     */
    public function getUseStatements(): UseStatementsInterface
    {
        return $this->useStatements;
    }

    /**
     * Gets properties
     * @link http://php.net/manual/en/reflectionclass.getproperties.php
     * @return ReflectionProperty[]
     * @since 5.0
     */
    public function getProperties(): array
    {
        if ($this->properties !== null) {
            return $this->properties;
        }

        $this->properties = [];
        $this->indexedProperties = [];
        $parentClass = $this->getParentClass();

        if ($parentClass) {
            $this->properties = $parentClass->getProperties();
        }

        foreach ($this->nativeReflClass->getProperties() as $property) {
            $this->properties[] = $this->indexedProperties[$property->getName()]
                = new ReflectionProperty($property, $this);
        }

        return $this->properties;
    }

    /**
     * @return ReflectionProperty[]
     */
    public function getIndexedProperties(): array
    {
        $this->getProperties();

        return $this->indexedProperties;
    }

    /**
     * Gets parent class
     * @link http://php.net/manual/en/reflectionclass.getparentclass.php
     * @return ReflectionClassInterface|null
     * @since 5.0
     */
    public function getParentClass(): ?ReflectionClassInterface
    {
        return $this->parentClass;
    }

    /**
     * Gets class name
     * @link http://php.net/manual/en/reflectionclass.getname.php
     * @return string The class name.
     * @since 5.0
     */
    public function getName(): string
    {
        return $this->nativeReflClass->getName();
    }

    /**
     * Checks if class is defined internally by an extension, or the core
     * @link http://php.net/manual/en/reflectionclass.isinternal.php
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * @since 5.0
     */
    public function isInternal(): bool
    {
        return $this->nativeReflClass->isInternal();
    }

    /**
     * Checks if user defined
     * @link http://php.net/manual/en/reflectionclass.isuserdefined.php
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * @since 5.0
     */
    public function isUserDefined(): bool
    {
        return $this->nativeReflClass->isUserDefined();
    }

    /**
     * Checks if the class is instantiable
     * @link http://php.net/manual/en/reflectionclass.isinstantiable.php
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * @since 5.0
     */
    public function isInstantiable(): bool
    {
        return $this->nativeReflClass->isInstantiable();
    }

    /**
     * Returns whether this class is cloneable
     * @link http://php.net/manual/en/reflectionclass.iscloneable.php
     * @return bool <b>TRUE</b> if the class is cloneable, <b>FALSE</b> otherwise.
     * @since 5.4.0
     */
    public function isCloneable(): bool
    {
        return $this->nativeReflClass->isCloneable();
    }

    /**
     * Gets the filename of the file in which the class has been defined
     * @link http://php.net/manual/en/reflectionclass.getfilename.php
     * @return string the filename of the file in which the class has been defined.
     * If the class is defined in the PHP core or in a PHP extension, <b>FALSE</b>
     * is returned.
     * @since 5.0
     */
    public function getFileName(): string
    {
        return $this->nativeReflClass->getFileName();
    }

    /**
     * Gets starting line number
     * @link http://php.net/manual/en/reflectionclass.getstartline.php
     * @return int The starting line number, as an integer.
     * @since 5.0
     */
    public function getStartLine(): int
    {
        return $this->nativeReflClass->getStartLine();
    }

    /**
     * Gets end line
     * @link http://php.net/manual/en/reflectionclass.getendline.php
     * @return int The ending line number of the user defined class, or <b>FALSE</b> if unknown.
     * @since 5.0
     */
    public function getEndLine(): int
    {
        return $this->nativeReflClass->getEndLine();
    }

    /**
     * Gets doc comments
     * @link http://php.net/manual/en/reflectionclass.getdoccomment.php
     * @return string The doc comment if it exists, otherwise <b>FALSE</b>
     * @since 5.1.0
     */
    public function getDocComment(): string
    {
        return $this->nativeReflClass->getDocComment();
    }

    /**
     * Gets the constructor of the class
     * @link http://php.net/manual/en/reflectionclass.getconstructor.php
     * @return ReflectionMethodInterface A <b>ReflectionMethod</b> object reflecting the class' constructor,
     * or <b>NULL</b> if the class
     * has no constructor.
     * @since 5.0
     */
    public function getConstructor(): ?ReflectionMethodInterface
    {
        if ($this->constructor !== '') {
            return $this->constructor;
        }

        $nativeConstructor = $this->nativeReflClass->getConstructor();

        if (!$nativeConstructor) {
            $this->constructor = null;

            return $this->constructor;
        }

        $this->constructor = new ReflectionMethod($nativeConstructor, $this);

        return $this->constructor;
    }

    /**
     * Checks if method is defined
     * @link http://php.net/manual/en/reflectionclass.hasmethod.php
     * @param string $name <p>
     * Name of the method being checked for.
     * </p>
     * @return bool <b>TRUE</b> if it has the method, otherwise <b>FALSE</b>
     * @throws ReflectionException
     */
    public function hasMethod(string $name): bool
    {
        $methods = $this->getIndexedMethods();

        return isset($methods[$name]);
    }

    /**
     * Gets a <b>ReflectionMethod</b> for a class method.
     * @link http://php.net/manual/en/reflectionclass.getmethod.php
     * @param string $name <p>
     * The method name to reflect.
     * </p>
     * @return ReflectionMethod A <b>ReflectionMethod</b>.
     * @throws ReflectionException
     * @throws RuntimeException
     */
    public function getMethod(string $name): ReflectionMethod
    {
        $methods = $this->getIndexedMethods();

        if (!isset($methods[$name])) {
            throw new RuntimeException(sprintf("Method '%s' doesn't exist in class '%s'.", $name, $this->getName()));
        }

        return $methods[$name];
    }

    /**
     * Gets an array of methods
     * @link http://php.net/manual/en/reflectionclass.getmethods.php
     * @param string $filter [optional] <p>
     * Filter the results to include only methods with certain attributes. Defaults
     * to no filtering.
     * </p>
     * <p>
     * Any combination of <b>ReflectionMethod::IS_STATIC</b>,
     * <b>ReflectionMethod::IS_PUBLIC</b>,
     * <b>ReflectionMethod::IS_PROTECTED</b>,
     * <b>ReflectionMethod::IS_PRIVATE</b>,
     * <b>ReflectionMethod::IS_ABSTRACT</b>,
     * <b>ReflectionMethod::IS_FINAL</b>.
     * </p>
     * @return ReflectionMethod[] An array of methods.
     * @throws ReflectionException
     */
    public function getMethods(string $filter = null): array
    {
        if ($this->methods !== null) {
            return $this->methods;
        }

        $this->methods = [];
        $this->indexedMethods = [];

        foreach ($this->nativeReflClass->getMethods($filter) as $method) {
            $this->methods[] = $this->indexedMethods[$method->getName()] = new ReflectionMethod($method, $this);
        }

        return $this->methods;
    }

    /**
     * @param string $filter [optional] <p>
     * Filter the results to include only methods with certain attributes. Defaults
     * to no filtering.
     * </p>
     * @return ReflectionMethod[]
     * @throws ReflectionException
     */
    public function getIndexedMethods(string $filter = null): array
    {
        if ($this->indexedMethods !== null) {
            return $this->indexedMethods;
        }

        $this->getMethods($filter);

        return $this->indexedMethods;
    }

    /**
     * Checks if property is defined
     * @link http://php.net/manual/en/reflectionclass.hasproperty.php
     * @param string $name <p>
     * Name of the property being checked for.
     * </p>
     * @return bool <b>TRUE</b> if it has the property, otherwise <b>FALSE</b>
     * @since 5.1.0
     */
    public function hasProperty(string $name): bool
    {
        $properties = $this->getIndexedProperties();

        return isset($properties[$name]);
    }

    /**
     * Gets a <b>ReflectionProperty</b> for a class's property
     * @link http://php.net/manual/en/reflectionclass.getproperty.php
     * @param string $name <p>
     * The property name.
     * </p>
     * @return ReflectionProperty A <b>ReflectionProperty</b>.
     * @since 5.0
     * @throws RuntimeException
     */
    public function getProperty(string $name): ReflectionProperty
    {
        $properties = $this->getIndexedProperties();

        if (!isset($properties[$name])) {
            throw new RuntimeException(sprintf("Property '%s' doesn't exist in class '%s'.", $name, $this->getName()));
        }

        return $properties[$name];
    }

    /**
     * Checks if constant is defined
     * @link http://php.net/manual/en/reflectionclass.hasconstant.php
     * @param string $name <p>
     * The name of the constant being checked for.
     * </p>
     * @return bool <b>TRUE</b> if the constant is defined, otherwise <b>FALSE</b>.
     * @since 5.1.0
     */
    public function hasConstant(string $name): bool
    {
        return $this->nativeReflClass->hasConstant($name);
    }

    /**
     * Gets constants
     * @link http://php.net/manual/en/reflectionclass.getconstants.php
     * @return array An array of constants.
     * Constant name in key, constant value in value.
     * @since 5.0
     */
    public function getConstants(): array
    {
        return $this->nativeReflClass->getConstants();
    }

    /**
     * Gets defined constant
     * @link http://php.net/manual/en/reflectionclass.getconstant.php
     * @param string $name <p>
     * Name of the constant.
     * </p>
     * @return mixed Value of the constant.
     * @since 5.0
     */
    public function getConstant(string $name)
    {
        return $this->nativeReflClass->getConstant($name);
    }

    /**
     * Gets the interfaces
     * @link http://php.net/manual/en/reflectionclass.getinterfaces.php
     * @return ReflectionClass[] An associative array of interfaces, with keys as interface
     * names and the array values as <b>ReflectionClass</b> objects.
     * @since 5.0
     */
    public function getInterfaces(): array
    {
        return $this->nativeReflClass->getInterfaces();
    }

    /**
     * Gets the interface names
     * @link http://php.net/manual/en/reflectionclass.getinterfacenames.php
     * @return string[] A numerical array with interface names as the values.
     * @since 5.2.0
     */
    public function getInterfaceNames(): array
    {
        return $this->nativeReflClass->getInterfaceNames();
    }

    /**
     * Checks if the class is anonymous
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * @since 7.0
     */
    public function isAnonymous(): bool
    {
        return $this->nativeReflClass->isAnonymous();
    }

    /**
     * Checks if the class is an interface
     * @link http://php.net/manual/en/reflectionclass.isinterface.php
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * @since 5.0
     */
    public function isInterface(): bool
    {
        return $this->nativeReflClass->isInterface();
    }

    /**
     * Returns an array of traits used by this class
     * @link http://php.net/manual/en/reflectionclass.gettraits.php
     * @return ReflectionClass[]|null an array with trait names in keys and instances of trait's
     * <b>ReflectionClass</b> in values.
     * Returns <b>NULL</b> in case of an error.
     * @since 5.4.0
     */
    public function getTraits(): ?array
    {
        return $this->nativeReflClass->getTraits();
    }

    /**
     * Returns an array of names of traits used by this class
     * @link http://php.net/manual/en/reflectionclass.gettraitnames.php
     * @return string[]|null an array with trait names in values.
     * Returns <b>NULL</b> in case of an error.
     * @since 5.4.0
     */
    public function getTraitNames(): ?array
    {
        return $this->nativeReflClass->getTraitNames();
    }

    /**
     * Returns an array of trait aliases
     * @link http://php.net/manual/en/reflectionclass.gettraitaliases.php
     * @return array an array with new method names in keys and original names (in the
     * format "TraitName::original") in values.
     * Returns <b>NULL</b> in case of an error.
     * @since 5.4.0
     */
    public function getTraitAliases(): ?array
    {
        return $this->nativeReflClass->getTraitAliases();
    }

    /**
     * Returns whether this is a trait
     * @link http://php.net/manual/en/reflectionclass.istrait.php
     * @return bool <b>TRUE</b> if this is a trait, <b>FALSE</b> otherwise.
     * Returns <b>NULL</b> in case of an error.
     * @since 5.4.0
     */
    public function isTrait(): ?bool
    {
        return $this->nativeReflClass->isTrait();
    }

    /**
     * Checks if class is abstract
     * @link http://php.net/manual/en/reflectionclass.isabstract.php
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * @since 5.0
     */
    public function isAbstract(): bool
    {
        return $this->nativeReflClass->isAbstract();
    }

    /**
     * Checks if class is final
     * @link http://php.net/manual/en/reflectionclass.isfinal.php
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * @since 5.0
     */
    public function isFinal(): bool
    {
        return $this->nativeReflClass->isFinal();
    }

    /**
     * Gets modifiers
     * @link http://php.net/manual/en/reflectionclass.getmodifiers.php
     * @return int bitmask of
     * modifier constants.
     * @since 5.0
     */
    public function getModifiers(): int
    {
        return $this->nativeReflClass->getModifiers();
    }

    /**
     * Checks class for instance
     * @link http://php.net/manual/en/reflectionclass.isinstance.php
     * @param object $object <p>
     * The object being compared to.
     * </p>
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * @since 5.0
     */
    public function isInstance($object): bool
    {
        return $this->nativeReflClass->isInstance($object);
    }

    /**
     * Creates a new class instance from given arguments.
     * @link http://php.net/manual/en/reflectionclass.newinstance.php
     * @param mixed $args [optional]<p>
     * Accepts a variable number of arguments which are passed to the class
     * constructor, much like <b>call_user_func</b>.
     * </p>
     * @param mixed $optional [optional]
     * @return object
     * @since 5.0
     */
    public function newInstance($args = null, $optional = null)
    {
        return $this->nativeReflClass->newInstance($args, $optional);
    }

    /**
     * Creates a new class instance without invoking the constructor.
     * @link http://php.net/manual/en/reflectionclass.newinstancewithoutconstructor.php
     * @return object
     * @since 5.4.0
     */
    public function newInstanceWithoutConstructor()
    {
        return $this->nativeReflClass->newInstanceWithoutConstructor();
    }

    /**
     * Creates a new class instance from given arguments.
     * @link http://php.net/manual/en/reflectionclass.newinstanceargs.php
     * @param array $args [optional] <p>
     * The parameters to be passed to the class constructor as an array.
     * </p>
     * @return object a new instance of the class.
     * @since 5.1.3
     */
    public function newInstanceArgs(array $args = null)
    {
        return $this->nativeReflClass->newInstanceArgs($args);
    }

    /**
     * Checks if a subclass
     * @link http://php.net/manual/en/reflectionclass.issubclassof.php
     * @param string $class <p>
     * The class name being checked against.
     * </p>
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * @since 5.0
     */
    public function isSubclassOf(string $class): bool
    {
        return $this->nativeReflClass->isSubclassOf($class);
    }

    /**
     * Gets static properties
     * @link http://php.net/manual/en/reflectionclass.getstaticproperties.php
     * @return array The static properties, as an array.
     * @since 5.0
     */
    public function getStaticProperties(): array
    {
        return $this->nativeReflClass->getStaticProperties();
    }

    /**
     * Gets static property value
     * @link http://php.net/manual/en/reflectionclass.getstaticpropertyvalue.php
     * @param string $name <p>
     * The name of the static property for which to return a value.
     * </p>
     * @param string $default [optional] <p>
     * </p>
     * @return mixed The value of the static property.
     * @since 5.1.0
     */
    public function getStaticPropertyValue(string $name, string $default = null)
    {
        return $this->nativeReflClass->getStaticPropertyValue($name, $default);
    }

    /**
     * Sets static property value
     * @link http://php.net/manual/en/reflectionclass.setstaticpropertyvalue.php
     * @param string $name <p>
     * Property name.
     * </p>
     * @param string $value <p>
     * New property value.
     * </p>
     * @return void No value is returned.
     * @since 5.1.0
     */
    public function setStaticPropertyValue(string $name, string $value): void
    {
        $this->nativeReflClass->setStaticPropertyValue($name, $value);
    }

    /**
     * Gets default properties
     * @link http://php.net/manual/en/reflectionclass.getdefaultproperties.php
     * @return array An array of default properties, with the key being the name of
     * the property and the value being the default value of the property or <b>NULL</b>
     * if the property doesn't have a default value. The function does not distinguish
     * between static and non static properties and does not take visibility modifiers
     * into account.
     * @since 5.0
     */
    public function getDefaultProperties(): array
    {
        return $this->nativeReflClass->getDefaultProperties();
    }

    /**
     * Checks if iterateable
     * @link http://php.net/manual/en/reflectionclass.isiterateable.php
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * @since 5.0
     */
    public function isIterateable(): bool
    {
        return $this->nativeReflClass->isIterateable();
    }

    /**
     * Implements interface
     * @link http://php.net/manual/en/reflectionclass.implementsinterface.php
     * @param string $interface <p>
     * The interface name.
     * </p>
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * @since 5.0
     */
    public function implementsInterface(string $interface): bool
    {
        return $this->nativeReflClass->implementsInterface($interface);
    }

    /**
     * Gets a <b>ReflectionExtension</b> object for the extension which defined the class
     * @link http://php.net/manual/en/reflectionclass.getextension.php
     * @return ReflectionExtension A <b>ReflectionExtension</b> object representing the extension
     * which defined the class,
     * or <b>NULL</b> for user-defined classes.
     * @since 5.0
     */
    public function getExtension(): ReflectionExtension
    {
        return $this->nativeReflClass->getExtension();
    }

    /**
     * Gets the name of the extension which defined the class
     * @link http://php.net/manual/en/reflectionclass.getextensionname.php
     * @return string The name of the extension which defined the class, or <b>FALSE</b> for user-defined classes.
     * @since 5.0
     */
    public function getExtensionName(): string
    {
        return $this->nativeReflClass->getExtensionName();
    }

    /**
     * Checks if in namespace
     * @link http://php.net/manual/en/reflectionclass.innamespace.php
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     * @since 5.3.0
     */
    public function inNamespace(): bool
    {
        return $this->nativeReflClass->inNamespace();
    }

    /**
     * Gets namespace name
     * @link http://php.net/manual/en/reflectionclass.getnamespacename.php
     * @return string The namespace name.
     * @since 5.3.0
     */
    public function getNamespaceName(): string
    {
        return $this->nativeReflClass->getNamespaceName();
    }

    /**
     * Gets short name
     * @link http://php.net/manual/en/reflectionclass.getshortname.php
     * @return string The class short name.
     * @since 5.3.0
     */
    public function getShortName(): string
    {
        return $this->nativeReflClass->getShortName();
    }
}

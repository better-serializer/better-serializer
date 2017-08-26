<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Reflection;

use ReflectionExtension;
use ReflectionType;

/**
 * Interface AbstractReflectionFunctionInterface
 * @package BetterSerializer\Reflection
 */
interface AbstractReflectionFunctionInterface
{

    /**
     * Checks if function in namespace
     * @link http://php.net/manual/en/reflectionfunctionabstract.innamespace.php
     * @return bool <b>TRUE</b> if it's in a namespace, otherwise <b>FALSE</b>
     * @since 5.3.0
     */
    public function inNamespace(): bool;

    /**
     * Checks if closure
     * @link http://php.net/manual/en/reflectionfunctionabstract.isclosure.php
     * @return bool <b>TRUE</b> if it's a closure, otherwise <b>FALSE</b>
     * @since 5.3.0
     */
    public function isClosure(): bool;

    /**
     * Checks if deprecated
     * @link http://php.net/manual/en/reflectionfunctionabstract.isdeprecated.php
     * @return bool <b>TRUE</b> if it's deprecated, otherwise <b>FALSE</b>
     * @since 5.0
     */
    public function isDeprecated(): bool;

    /**
     * Checks if is internal
     * @link http://php.net/manual/en/reflectionfunctionabstract.isinternal.php
     * @return bool <b>TRUE</b> if it's internal, otherwise <b>FALSE</b>
     * @since 5.0
     */
    public function isInternal(): bool;

    /**
     * Checks if user defined
     * @link http://php.net/manual/en/reflectionfunctionabstract.isuserdefined.php
     * @return bool <b>TRUE</b> if it's user-defined, otherwise false;
     * @since 5.0
     */
    public function isUserDefined(): bool;

    /**
     * Gets doc comment
     * @link http://php.net/manual/en/reflectionfunctionabstract.getdoccomment.php
     * @return string The doc comment if it exists, otherwise <b>FALSE</b>
     * @since 5.1.0
     */
    public function getDocComment(): string;

    /**
     * Gets end line number
     * @link http://php.net/manual/en/reflectionfunctionabstract.getendline.php
     * @return int The ending line number of the user defined function, or <b>FALSE</b> if unknown.
     * @since 5.0
     */
    public function getEndLine(): int;

    /**
     * Gets extension info
     * @link http://php.net/manual/en/reflectionfunctionabstract.getextension.php
     * @return ReflectionExtension The extension information, as a <b>ReflectionExtension</b> object.
     * @since 5.0
     */
    public function getExtension(): ReflectionExtension;

    /**
     * Gets extension name
     * @link http://php.net/manual/en/reflectionfunctionabstract.getextensionname.php
     * @return string The extensions name.
     * @since 5.0
     */
    public function getExtensionName(): string;

    /**
     * Gets file name
     * @link http://php.net/manual/en/reflectionfunctionabstract.getfilename.php
     * @return string The file name.
     * @since 5.0
     */
    public function getFileName(): string;

    /**
     * Gets function name
     * @link http://php.net/manual/en/reflectionfunctionabstract.getname.php
     * @return string The name of the function.
     * @since 5.0
     */
    public function getName(): string;

    /**
     * Gets namespace name
     * @link http://php.net/manual/en/reflectionfunctionabstract.getnamespacename.php
     * @return string The namespace name.
     * @since 5.3.0
     */
    public function getNamespaceName(): string;

    /**
     * Gets number of parameters
     * @link http://php.net/manual/en/reflectionfunctionabstract.getnumberofparameters.php
     * @return int The number of parameters.
     * @since 5.0.3
     */
    public function getNumberOfParameters(): int;

    /**
     * Gets number of required parameters
     * @link http://php.net/manual/en/reflectionfunctionabstract.getnumberofrequiredparameters.php
     * @return int The number of required parameters.
     * @since 5.0.3
     */
    public function getNumberOfRequiredParameters(): int;

    /**
     * Gets parameters
     * @link http://php.net/manual/en/reflectionfunctionabstract.getparameters.php
     * @return ReflectionParameter[] The parameters, as a ReflectionParameter objects.
     * @since 5.0
     */
    public function getParameters(): array;

    /**
     * Gets the specified return type of a function
     * @link http://php.net/manual/en/reflectionfunctionabstract.getreturntype.php
     * @return ReflectionType|NULL Returns a ReflectionType object if a return type is specified, NULL otherwise.
     * @since 7.0
     */
    public function getReturnType(): ?ReflectionType;

    /**
     * Gets function short name
     * @link http://php.net/manual/en/reflectionfunctionabstract.getshortname.php
     * @return string The short name of the function.
     * @since 5.3.0
     */
    public function getShortName(): string;

    /**
     * Gets starting line number
     * @link http://php.net/manual/en/reflectionfunctionabstract.getstartline.php
     * @return int The starting line number.
     * @since 5.0
     */
    public function getStartLine(): int;

    /**
     * Gets static variables
     * @link http://php.net/manual/en/reflectionfunctionabstract.getstaticvariables.php
     * @return array An array of static variables.
     * @since 5.0
     */
    public function getStaticVariables(): array;

    /**
     * Checks if the function has a specified return type
     * @link http://php.net/manual/en/reflectionfunctionabstract.hasreturntype.php
     * @return bool Returns TRUE if the function is a specified return type, otherwise FALSE.
     * @since 7.0
     */
    public function hasReturnType(): bool;

    /**
     * Checks if returns reference
     * @link http://php.net/manual/en/reflectionfunctionabstract.returnsreference.php
     * @return bool <b>TRUE</b> if it returns a reference, otherwise <b>FALSE</b>
     * @since 5.0
     */
    public function returnsReference(): bool;

    /**
     * Returns whether this function is a generator
     * @link http://php.net/manual/en/reflectionfunctionabstract.isgenerator.php
     * @return bool <b>TRUE</b> if the function is generator, otherwise <b>FALSE</b>
     * @since 5.5.0
     */
    public function isGenerator(): bool;

    /**
     * Returns whether this function is variadic
     * @link http://php.net/manual/en/reflectionfunctionabstract.isvariadic.php
     * @return bool <b>TRUE</b> if the function is variadic, otherwise <b>FALSE</b>
     * @since 5.6.0
     */
    public function isVariadic(): bool;
}

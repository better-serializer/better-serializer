<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Reflection\UseStatement;

use ReflectionClass;
use RuntimeException;

/**
 * Class CodeReader
 * @author mfris
 * @package BetterSerializer\Reflection\Factory
 */
interface CodeReaderInterface
{
    /**
     * Read file source up to the line where our class is defined.
     *
     * @param ReflectionClass $reflectionClass
     * @return string
     * @throws RuntimeException
     * @
     */
    public function readUseStatementsSource(ReflectionClass $reflectionClass): string;
}

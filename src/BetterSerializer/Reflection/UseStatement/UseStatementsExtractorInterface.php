<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Reflection\UseStatement;

use LogicException;
use ReflectionClass;
use RuntimeException;

/**
 * Class UseStatementsExtractor
 * @author mfris
 * @package BetterSerializer\Reflection\UseStatement
 */
interface UseStatementsExtractorInterface
{
    /**
     * @param ReflectionClass $reflectionClass
     * @return UseStatementsInterface
     * @throws LogicException
     * @throws RuntimeException
     */
    public function newUseStatements(ReflectionClass $reflectionClass): UseStatementsInterface;
}

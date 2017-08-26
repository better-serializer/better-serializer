<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType;

use BetterSerializer\Reflection\ReflectionClassInterface;
use LogicException;

/**
 * Class StringTypedPropertyContext
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 */
final class ContextStringFormType implements ContextStringFormTypeInterface
{

    /**
     * @var string
     */
    private $stringType;

    /**
     * @var ReflectionClassInterface
     */
    private $reflectionClass;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var bool
     */
    private $isClass;

    /**
     * StringTypedPropertyContext constructor.
     * @param string $stringType
     * @param ReflectionClassInterface $reflectionClass
     * @throws LogicException
     */
    public function __construct(string $stringType, ReflectionClassInterface $reflectionClass)
    {
        $this->reflectionClass = $reflectionClass;
        $this->stringType = $this->getAnalyzedType($stringType);
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        if ($this->namespace === null) {
            $this->namespace = $this->reflectionClass->getNamespaceName();
        }

        return $this->namespace;
    }

    /**
     * @return ReflectionClassInterface
     */
    public function getReflectionClass(): ReflectionClassInterface
    {
        return $this->reflectionClass;
    }

    /**
     * @return string
     */
    public function getStringType(): string
    {
        return $this->stringType;
    }

    /**
     * @return bool
     */
    public function isClass(): bool
    {
        return $this->isClass;
    }

    /**
     * @param string $stringType
     * @return string
     * @throws LogicException
     */
    private function getAnalyzedType(string $stringType): string
    {
        $this->isClass = false;
        $isPotentialClass = preg_match("/^[a-zA-Z0-9_\\\]+[^\\]]$/", $stringType, $matches);

        if (!$isPotentialClass) {
            return $stringType;
        }

        $potentialClass = $this->resolvePotentialClass($matches[0]);

        return $potentialClass !== '' ? $potentialClass : $stringType;
    }

    /**
     * @param string $potentialClass
     * @return string
     * @throws LogicException
     */
    private function resolvePotentialClass(string $potentialClass): string
    {
        $potentialClass = ltrim($potentialClass, '\\');
        $fragments = new NamespaceFragments($potentialClass);
        $firstFragment = $fragments->getFirst();

        $expectedClass = $this->getNamespace() . '\\' . $potentialClass;
        $useStatements = $this->reflectionClass->getUseStatements();

        if ($useStatements->hasByIdentifier($firstFragment)) {
            $useStatement = $useStatements->getByIdentifier($firstFragment);
            $expectedClass = $useStatement->getFqdn() . '\\' . $fragments->getWithoutFirst();
        } elseif ($useStatements->hasByAlias($firstFragment)) {
            $useStatement = $useStatements->getByAlias($firstFragment);
            $expectedClass = $useStatement->getFqdn() . '\\' . $fragments->getWithoutFirst();
        }

        $expectedClass = rtrim($expectedClass, '\\');
        if (class_exists($expectedClass, false) || class_exists($expectedClass)) {
            $this->isClass = true;

            return $expectedClass;
        }

        $potentialClass = rtrim($potentialClass, '\\');
        if (class_exists($potentialClass, false) || class_exists($potentialClass)) {
            $this->isClass = true;

            return $potentialClass;
        }

        return '';
    }
}

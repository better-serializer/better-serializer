<?php
declare(strict_types=1);

/*
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
     * @var ContextStringFormType|null
     */
    private $collectionKeyType;

    /**
     * @var ContextStringFormType|null
     */
    private $collectionValueType;

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
    private $isClass = false;

    /**
     * @var bool
     */
    private $isInterface = false;

    /**
     * StringTypedPropertyContext constructor.
     * @param string $stringType
     * @param ReflectionClassInterface $reflectionClass
     * @throws LogicException
     */
    public function __construct(string $stringType, ReflectionClassInterface $reflectionClass)
    {
        $this->reflectionClass = $reflectionClass;
        $this->analyzeType($stringType);
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
     * @return ContextStringFormTypeInterface|null
     */
    public function getCollectionKeyType(): ?ContextStringFormTypeInterface
    {
        return $this->collectionKeyType;
    }

    /**
     * @return ContextStringFormTypeInterface|null
     */
    public function getCollectionValueType(): ?ContextStringFormTypeInterface
    {
        return $this->collectionValueType;
    }

    /**
     * @return bool
     */
    public function isClass(): bool
    {
        return $this->isClass;
    }

    /**
     * @return bool
     */
    public function isInterface(): bool
    {
        return $this->isInterface;
    }

    /**
     * @return bool
     */
    public function isClassOrInterface(): bool
    {
        return $this->isClass || $this->isInterface;
    }

    /**
     * @param string $stringType
     * @return void
     * @throws LogicException
     */
    private function analyzeType(string $stringType): void
    {
        $isPotentialClass = preg_match(
            "/^(?P<type>[a-zA-Z0-9_\\\]+[^\\\])(<(?P<keyOrValue>[A-Za-z][A-Za-z0-9_\\\]+[^\\\])"
            . "(\s*,\s*(?P<value>[A-Za-z][A-Za-z0-9_\\\]+[^\\\]))?>)?$/",
            $stringType,
            $matches
        );

        if (!$isPotentialClass) {
            $this->stringType = $stringType;

            return;
        }

        $potentialClass = $this->resolvePotentialClass($matches['type']);
        $this->stringType = $potentialClass !== '' ? $potentialClass : $stringType;

        if (isset($matches['keyOrValue'], $matches['value'])) {
            $this->collectionKeyType = new self($matches['keyOrValue'], $this->reflectionClass);
            $this->collectionValueType = new self($matches['value'], $this->reflectionClass);
        } elseif (isset($matches['keyOrValue'])) {
            $this->collectionValueType = new self($matches['keyOrValue'], $this->reflectionClass);
        }
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
        $potentialClass = rtrim($potentialClass, '\\');

        $classCandidates = [$expectedClass, $potentialClass];

        foreach ($classCandidates as $classCandidate) {
            if ($this->checkClassExistence($classCandidate)) {
                $this->isClass = true;

                return $classCandidate;
            }

            if ($this->checkInterfaceExistence($classCandidate)) {
                $this->isInterface = true;

                return $classCandidate;
            }
        }

        return '';
    }

    /**
     * @param string $className
     * @return bool
     */
    private function checkClassExistence(string $className): bool
    {
        return(class_exists($className, false) || class_exists($className));
    }

    /**
     * @param string $interfaceName
     * @return bool
     */
    private function checkInterfaceExistence(string $interfaceName): bool
    {
        return(interface_exists($interfaceName, false) || interface_exists($interfaceName));
    }
}

<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parameters\ParametersInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeClassEnumInterface;

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
     * @var string
     */
    private $namespace;

    /**
     * @var TypeClassEnumInterface
     */
    private $typeClass;

    /**
     * @var ParametersInterface|null
     */
    private $parameters;

    /**
     * @var ContextStringFormType|null
     */
    private $collectionValueType;

    /**
     * @var ContextStringFormType|null
     */
    private $collectionKeyType;

    /**
     * @param string $stringType
     * @param string $namespace
     * @param TypeClassEnumInterface $typeClass
     * @param ParametersInterface|null $parameters
     * @param ContextStringFormTypeInterface|null $collectionValueType
     * @param ContextStringFormTypeInterface|null $collectionKeyType
     */
    public function __construct(
        string $stringType,
        string $namespace,
        TypeClassEnumInterface $typeClass,
        ?ParametersInterface $parameters = null,
        ?ContextStringFormTypeInterface $collectionValueType = null,
        ?ContextStringFormTypeInterface $collectionKeyType = null
    ) {
        $this->stringType = $stringType;
        $this->namespace = $namespace;
        $this->typeClass = $typeClass;
        $this->parameters = $parameters;
        $this->collectionValueType = $collectionValueType;
        $this->collectionKeyType = $collectionKeyType;
    }

    /**
     * @return string
     */
    public function getStringType(): string
    {
        return $this->stringType;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @return TypeClassEnumInterface
     */
    public function getTypeClass(): TypeClassEnumInterface
    {
        return $this->typeClass;
    }

    /**
     * @return null|ParametersInterface
     */
    public function getParameters(): ?ParametersInterface
    {
        return $this->parameters;
    }

    /**
     * @return ContextStringFormTypeInterface|null
     */
    public function getCollectionValueType(): ?ContextStringFormTypeInterface
    {
        return $this->collectionValueType;
    }

    /**
     * @return ContextStringFormTypeInterface|null
     */
    public function getCollectionKeyType(): ?ContextStringFormTypeInterface
    {
        return $this->collectionKeyType;
    }
}

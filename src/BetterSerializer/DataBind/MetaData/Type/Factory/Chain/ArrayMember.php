<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\ContextStringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\ArrayType;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeEnum;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;

/**
 * Class SimpleMember
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type\Factory\Chain
 */
final class ArrayMember extends ChainMember
{

    /**
     * @var TypeFactoryInterface
     */
    private $typeFactory;

    /**
     * ArrayMember constructor.
     * @param TypeFactoryInterface $typeFactory
     */
    public function __construct(TypeFactoryInterface $typeFactory)
    {
        $this->typeFactory = $typeFactory;
    }

    /**
     * @param ContextStringFormTypeInterface $stringFormType
     * @return bool
     */
    protected function isProcessable(ContextStringFormTypeInterface $stringFormType): bool
    {
        return $stringFormType->getStringType() === TypeEnum::ARRAY && $stringFormType->getCollectionValueType();
    }

    /**
     * @param ContextStringFormTypeInterface $stringFormType
     * @return TypeInterface
     */
    protected function createType(ContextStringFormTypeInterface $stringFormType): TypeInterface
    {
        $nestedStringFormType = $stringFormType->getCollectionValueType();
        $subType = $this->typeFactory->getType($nestedStringFormType);

        return new ArrayType($subType);
    }
}

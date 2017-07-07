<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\Context\DerivedStringTypedPropertyContext;
use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\Context\StringTypedPropertyContextInterface;
use BetterSerializer\DataBind\MetaData\Type\ArrayType;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
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
     * @var string
     */
    private $stringSubType;

    /**
     * ArrayMember constructor.
     * @param TypeFactoryInterface $typeFactory
     */
    public function __construct(TypeFactoryInterface $typeFactory)
    {
        $this->typeFactory = $typeFactory;
    }

    /**
     * @param StringTypedPropertyContextInterface $context
     * @return bool
     */
    protected function isProcessable(StringTypedPropertyContextInterface $context): bool
    {
        if (preg_match('/array<([^>]+)>/', $context->getStringType(), $matches)) {
            $this->stringSubType = $matches[1];

            return true;
        }

        return false;
    }

    /**
     * @param StringTypedPropertyContextInterface $context
     * @return TypeInterface
     */
    protected function createType(StringTypedPropertyContextInterface $context): TypeInterface
    {
        $subContect = new DerivedStringTypedPropertyContext($this->stringSubType, $context->getNamespace());
        $subType = $this->typeFactory->getType($subContect);

        return new ArrayType($subType);
    }
}

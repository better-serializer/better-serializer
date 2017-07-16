<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\StringType\StringType;
use BetterSerializer\DataBind\MetaData\Type\StringType\StringTypeInterface;
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
     * @param StringTypeInterface $stringType
     * @return bool
     */
    protected function isProcessable(StringTypeInterface $stringType): bool
    {
        if (preg_match('/array<([^>]+)>/', $stringType->getStringType(), $matches)) {
            $this->stringSubType = $matches[1];

            return true;
        }

        return false;
    }

    /**
     * @param StringTypeInterface $stringType
     * @return TypeInterface
     */
    protected function createType(StringTypeInterface $stringType): TypeInterface
    {
        $subContect = new StringType($this->stringSubType, $stringType->getNamespace());
        $subType = $this->typeFactory->getType($subContect);

        return new ArrayType($subType);
    }
}

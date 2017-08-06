<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\StringFormType;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\StringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\ArrayType;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;

/**
 * Class SimpleMember
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type\Factory\Chain
 */
final class DocBlockArrayMember extends ChainMember
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
     * @param StringFormTypeInterface $stringType
     * @return bool
     */
    protected function isProcessable(StringFormTypeInterface $stringType): bool
    {
        if (preg_match('/([^\[]+)\[\]/', $stringType->getStringType(), $matches)) {
            $this->stringSubType = $matches[1];

            return true;
        }

        return false;
    }

    /**
     * @param StringFormTypeInterface $stringType
     * @return TypeInterface
     */
    protected function createType(StringFormTypeInterface $stringType): TypeInterface
    {
        $subContext = new StringFormType($this->stringSubType, $stringType->getNamespace());
        $subType = $this->typeFactory->getType($subContext);

        return new ArrayType($subType);
    }
}

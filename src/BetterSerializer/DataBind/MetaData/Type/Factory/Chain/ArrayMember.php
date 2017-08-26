<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\ContextStringFormType;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\ContextStringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\FqdnStringFormType;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\StringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\ArrayType;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\Reflection\ReflectionClassInterface;
use LogicException;

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
     * @param StringFormTypeInterface $stringFormType
     * @return bool
     */
    protected function isProcessable(StringFormTypeInterface $stringFormType): bool
    {
        if (preg_match('/array<([^>]+)>/', $stringFormType->getStringType(), $matches)) {
            $this->stringSubType = $matches[1];

            return true;
        }

        return false;
    }

    /**
     * @param StringFormTypeInterface $stringFormType
     * @return TypeInterface
     * @throws LogicException
     */
    protected function createType(StringFormTypeInterface $stringFormType): TypeInterface
    {
        $subTypeCallback = function (string $stringSubType): StringFormTypeInterface {
            return new FqdnStringFormType($stringSubType);
        };

        if ($stringFormType instanceof ContextStringFormTypeInterface) {
            $subTypeCallback = function (string $stringSubType) use ($stringFormType): StringFormTypeInterface {
                return new ContextStringFormType($stringSubType, $stringFormType->getReflectionClass());
            };
        }

        $nestedStringFormType = $subTypeCallback($this->stringSubType);
        $subType = $this->typeFactory->getType($nestedStringFormType);

        return new ArrayType($subType);
    }
}

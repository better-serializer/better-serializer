<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory;

use BetterSerializer\DataBind\MetaData\Type\ArrayType;
use BetterSerializer\DataBind\MetaData\Type\BooleanType;
use BetterSerializer\DataBind\MetaData\Type\FloatType;
use BetterSerializer\DataBind\MetaData\Type\IntegerType;
use BetterSerializer\DataBind\MetaData\Type\InterfaceType;
use BetterSerializer\DataBind\MetaData\Type\ObjectType;
use BetterSerializer\DataBind\MetaData\Type\StringType;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\StringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeClassEnum;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\MetaData\Type\UnknownType;

/**
 * Class NativeTypeFactory
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type\Factory
 */
final class NativeTypeFactory implements NativeTypeFactoryInterface
{

    /**
     * @param StringFormTypeInterface $stringFormType
     * @return TypeInterface
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function getType(StringFormTypeInterface $stringFormType): TypeInterface
    {
        $stringType = $stringFormType->getStringType();
        $typeClass = $stringFormType->getTypeClass();

        switch (true) {
            case $stringType === 'array':
                return new ArrayType(new UnknownType());
            case $stringType === 'int':
                return new IntegerType();
            case $stringType === 'bool':
                return new BooleanType();
            case $stringType === 'float':
                return new FloatType();
            case $stringType === 'string':
                return new StringType();
            case $typeClass === TypeClassEnum::CLASS_TYPE():
                return new ObjectType($stringType);
            case $typeClass === TypeClassEnum::INTERFACE_TYPE():
                return new InterfaceType($stringType);
        }

        return new UnknownType();
    }
}

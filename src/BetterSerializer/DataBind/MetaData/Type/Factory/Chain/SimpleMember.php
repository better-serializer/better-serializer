<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\BooleanType;
use BetterSerializer\DataBind\MetaData\Type\FloatType;
use BetterSerializer\DataBind\MetaData\Type\IntegerType;
use BetterSerializer\DataBind\MetaData\Type\NullType;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\ContextStringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\StringType;
use BetterSerializer\DataBind\MetaData\Type\TypeClassEnum;
use BetterSerializer\DataBind\MetaData\Type\TypeEnum;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;

/**
 *
 */
final class SimpleMember extends ChainMember
{
    /**
     * @var string[]
     */
    private static $type2Instance = [
        TypeEnum::BOOLEAN => BooleanType::class,
        TypeEnum::NULL => NullType::class,
        TypeEnum::INTEGER => IntegerType::class,
        TypeEnum::FLOAT => FloatType::class,
        TypeEnum::STRING => StringType::class,
    ];

    /**
     * @param ContextStringFormTypeInterface $stringFormType
     * @return bool
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function isProcessable(ContextStringFormTypeInterface $stringFormType): bool
    {
        return $stringFormType->getTypeClass() === TypeClassEnum::PRIMITIVE_TYPE()
            && isset(self::$type2Instance[$stringFormType->getStringType()]);
    }

    /**
     * @param ContextStringFormTypeInterface $stringFormType
     * @return TypeInterface
     */
    protected function createType(ContextStringFormTypeInterface $stringFormType): TypeInterface
    {
        $className = self::$type2Instance[$stringFormType->getStringType()];

        return new $className();
    }
}

<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Type;

use LogicException;

/**
 * Class TypeFactory
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type
 */
final class TypeFactory implements TypeFactoryInterface
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
     * @param string $stringType
     * @return TypeInterface
     * @throws LogicException
     */
    public function getType(string $stringType): TypeInterface
    {
        if (isset(self::$type2Instance[$stringType])) {
            $className = self::$type2Instance[$stringType];

            return new $className();
        }

        if (class_exists($stringType)) {
            return new ObjectType($stringType);
        }

        throw new LogicException("Unknown type - '{$stringType}'");
    }
}

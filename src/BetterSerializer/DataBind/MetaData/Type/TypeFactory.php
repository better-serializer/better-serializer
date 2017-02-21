<?php
declare(strict_types = 1);

/**
 * @author  mfris
 */
namespace BetterSerializer\DataBind\MetaData\Type;

/**
 * Class TypeFactory
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type
 */
final class TypeFactory implements TypeFactoryInterface
{

    /**
     * @param string $stringType
     * @return TypeInterface
     */
    public function getType(string $stringType = null): TypeInterface
    {
        if ($stringType === null) {
            return new NullType();
        }
    }
}

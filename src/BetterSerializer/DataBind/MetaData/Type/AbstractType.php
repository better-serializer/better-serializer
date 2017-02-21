<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Type;

/**
 * Class AbstractDataType
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type
 */
abstract class AbstractType implements TypeInterface
{

    /**
     * @var TypeEnum
     */
    protected static $type;


    /**
     * @return TypeEnum
     */
    public function getType(): TypeEnum
    {
        return self::$type;
    }
}

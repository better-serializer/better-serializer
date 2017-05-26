<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Type\Chain;

use BetterSerializer\DataBind\MetaData\Type\BooleanType;
use BetterSerializer\DataBind\MetaData\Type\FloatType;
use BetterSerializer\DataBind\MetaData\Type\IntegerType;
use BetterSerializer\DataBind\MetaData\Type\NullType;
use BetterSerializer\DataBind\MetaData\Type\StringType;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;

/**
 * Class SimpleMember
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Type
 */
final class SimpleMember extends ChainMember
{

    /**
     * @var string[]
     */
    private static $string2TypeMapping = [
        'boolean' => BooleanType::class,
        'integer' => IntegerType::class,
        'double'  => FloatType::class,
        'string'  => StringType::class,
        'NULL'    => NullType::class,
    ];

    /**
     * @param mixed $data
     * @return bool
     */
    protected function isProcessable($data): bool
    {
        return isset(self::$string2TypeMapping[gettype($data)]);
    }

    /**
     * @param mixed $data
     * @return TypeInterface
     */
    protected function createType($data): TypeInterface
    {
        $className = self::$string2TypeMapping[gettype($data)];

        return new $className();
    }
}

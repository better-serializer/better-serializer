<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Converter\Factory;

use BetterSerializer\DataBind\Converter\BooleanConverter;
use BetterSerializer\DataBind\Converter\ConverterInterface;
use BetterSerializer\DataBind\Converter\FloatConverter;
use BetterSerializer\DataBind\Converter\IntegerConverter;
use BetterSerializer\DataBind\Converter\StringConverter;
use BetterSerializer\DataBind\MetaData\Type\TypeEnum;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use LogicException;

/**
 * Class Factory
 * @author mfris
 * @package BetterSerializer\DataBind\Converter\Factory
 */
final class ConverterFactory implements ConverterFactoryInterface
{

    /**
     * @var array
     */
    private static $type2Converter = [
        TypeEnum::BOOLEAN => BooleanConverter::class,
        TypeEnum::FLOAT => FloatConverter::class,
        TypeEnum::INTEGER => IntegerConverter::class,
        TypeEnum::STRING => StringConverter::class,
    ];

    /**
     * @param TypeInterface $type
     * @return ConverterInterface
     * @throws LogicException
     */
    public function newConverter(TypeInterface $type): ConverterInterface
    {
        $stringType = $type->getType()->getValue();

        if (!isset(self::$type2Converter[$stringType])) {
            throw new LogicException(sprintf('Unsupported type: %s', $stringType));
        }

        $converterClass = self::$type2Converter[$stringType];

        return new $converterClass;
    }
}

<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Converter;

use BetterSerializer\DataBind\Converter\BooleanConverter;
use BetterSerializer\DataBind\Converter\Factory\AbstractConverterFactory;
use BetterSerializer\DataBind\Converter\FloatConverter;
use BetterSerializer\DataBind\Converter\IntegerConverter;
use BetterSerializer\DataBind\Converter\StringConverter;
use BetterSerializer\DataBind\Converter\ToDateTimeConverter;
use BetterSerializer\DataBind\MetaData\Type\TypeEnum;

/**
 * Class Converter
 * @author mfris
 * @package BetterSerializer\DataBind\Converter\Converter
 */
final class ConverterFactory extends AbstractConverterFactory implements ConverterFactoryInterface
{

    /**
     * @var array
     */
    protected static $type2Converter = [
        TypeEnum::BOOLEAN_TYPE => BooleanConverter::class,
        TypeEnum::FLOAT_TYPE => FloatConverter::class,
        TypeEnum::INTEGER_TYPE => IntegerConverter::class,
        TypeEnum::STRING_TYPE => StringConverter::class,
        TypeEnum::DATETIME_TYPE => ToDateTimeConverter::class,
    ];
}

<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Converter;

use BetterSerializer\DataBind\Converter\BooleanConverter;
use BetterSerializer\DataBind\Converter\FloatConverter;
use BetterSerializer\DataBind\Converter\FromDateTimeConverter;
use BetterSerializer\DataBind\Converter\IntegerConverter;
use BetterSerializer\DataBind\Converter\StringConverter;
use BetterSerializer\DataBind\MetaData\Type\TypeEnum;
use BetterSerializer\DataBind\Converter\Factory\AbstractConverterFactory;

/**
 * Class Converter
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Converter
 */
final class ConverterFactory extends AbstractConverterFactory implements ConverterFactoryInterface
{

    /**
     * @var array
     */
    protected static $type2Converter = [
        TypeEnum::BOOLEAN => BooleanConverter::class,
        TypeEnum::FLOAT => FloatConverter::class,
        TypeEnum::INTEGER => IntegerConverter::class,
        TypeEnum::STRING => StringConverter::class,
        TypeEnum::DATETIME => FromDateTimeConverter::class,
    ];
}
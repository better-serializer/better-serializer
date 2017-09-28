<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Converter\Factory;

use BetterSerializer\DataBind\Converter\ConverterInterface;
use BetterSerializer\DataBind\Converter\TypeDependentConverterInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use LogicException;

/**
 * Class Converter
 * @author mfris
 * @package BetterSerializer\DataBind\Converter\Converter
 */
abstract class AbstractConverterFactory implements ConverterFactoryInterface
{

    /**
     * @var array
     */
    protected static $type2Converter;

    /**
     * @var bool[]
     */
    private static $isTypeDependent = [];

    /**
     * @param TypeInterface $type
     * @return ConverterInterface
     * @throws LogicException
     */
    public function newConverter(TypeInterface $type): ConverterInterface
    {
        $stringType = $type->getType()->getValue();

        if (!isset(static::$type2Converter[$stringType])) {
            throw new LogicException(sprintf('Unsupported type: %s', $stringType));
        }

        $converterClass = static::$type2Converter[$stringType];

        if ($this->isConverterTypeDependent($converterClass)) {
            return new $converterClass($type);
        }

        return new $converterClass;
    }

    /**
     * @param string $converterClass
     * @return bool
     */
    private function isConverterTypeDependent(string $converterClass): bool
    {
        if (isset(self::$isTypeDependent[$converterClass])) {
            return self::$isTypeDependent[$converterClass];
        }

        $interfaces = class_implements($converterClass);
        self::$isTypeDependent[$converterClass] = in_array(
            TypeDependentConverterInterface::class,
            $interfaces,
            true
        );

        return self::$isTypeDependent[$converterClass];
    }
}

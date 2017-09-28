<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Converter\Factory;

use BetterSerializer\DataBind\Converter\ConverterInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;

/**
 * Interface FactoryInterface
 * @package BetterSerializer\DataBind\Writer\Converter
 */
interface ConverterFactoryInterface
{

    /**
     * @param TypeInterface $type
     * @return ConverterInterface
     */
    public function newConverter(TypeInterface $type): ConverterInterface;
}

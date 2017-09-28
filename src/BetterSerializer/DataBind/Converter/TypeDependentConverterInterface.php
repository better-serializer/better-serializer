<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Converter;

use BetterSerializer\DataBind\MetaData\Type\TypeInterface;

/**
 * Interface ConverterInterface
 * @package BetterSerializer\DataBind\Converter
 */
interface TypeDependentConverterInterface extends ConverterInterface
{

    /**
     * FromDateTimeConverter constructor.
     * @param TypeInterface $type
     */
    public function __construct(TypeInterface $type);
}

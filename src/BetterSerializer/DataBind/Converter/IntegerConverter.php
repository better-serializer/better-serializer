<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Converter;

/**
 * Class IntegerConverter
 * @author mfris
 * @package BetterSerializer\DataBind\Converter
 */
final class IntegerConverter implements ConverterInterface
{

    /**
     * @param mixed $value
     * @return int
     */
    public function convert($value)
    {
        return (int) $value;
    }
}

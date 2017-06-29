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
final class BooleanConverter implements ConverterInterface
{

    /**
     * @param mixed $value
     * @return bool
     */
    public function convert($value)
    {
        return (bool) $value;
    }
}

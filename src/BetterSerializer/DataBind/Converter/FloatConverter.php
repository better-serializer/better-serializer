<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Converter;

/**
 * Class FloatConverter
 * @author mfris
 * @package BetterSerializer\DataBind\Converter
 */
final class FloatConverter implements ConverterInterface
{

    /**
     * @param mixed $value
     * @return float
     */
    public function convert($value)
    {
        return (float) $value;
    }
}

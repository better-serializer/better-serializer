<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Converter;

/**
 * Class StringConverter
 * @author mfris
 * @package BetterSerializer\DataBind\Converter
 */
final class StringConverter implements ConverterInterface
{

    /**
     * @param mixed $value
     * @return string
     */
    public function convert($value)
    {
        return (string) $value;
    }
}

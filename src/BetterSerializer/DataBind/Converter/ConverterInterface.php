<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Converter;

/**
 * Interface ConverterInterface
 * @package BetterSerializer\DataBind\Converter
 */
interface ConverterInterface
{

    /**
     * @param mixed $value
     * @return mixed
     */
    public function convert($value);
}

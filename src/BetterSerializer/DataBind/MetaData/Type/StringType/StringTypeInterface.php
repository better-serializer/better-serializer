<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringType;

/**
 * Class StringTypedPropertyContext
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 */
interface StringTypeInterface
{

    /**
     * @return string
     */
    public function getNamespace(): string;

    /**
     * @return string
     */
    public function getStringType(): string;
}

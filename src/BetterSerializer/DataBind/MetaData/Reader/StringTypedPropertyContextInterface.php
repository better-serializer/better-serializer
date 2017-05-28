<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader;

/**
 * Class StringTypedPropertyContext
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 */
interface StringTypedPropertyContextInterface
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

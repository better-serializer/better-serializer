<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type;

/**
 * Interface ObjectTypeInterface
 * @package BetterSerializer\DataBind\MetaData\Type
 */
interface ObjectTypeInterface extends ComplexTypeInterface
{

    /**
     * @return string
     */
    public function getClassName(): string;
}

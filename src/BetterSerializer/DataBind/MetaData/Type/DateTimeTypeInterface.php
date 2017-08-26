<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type;

/**
 * Interface DateTimeTypeInterface
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type
 */
interface DateTimeTypeInterface extends ObjectTypeInterface
{

    /**
     * @return string
     */
    public function getFormat(): string;
}

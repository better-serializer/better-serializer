<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type;

/**
 *
 */
interface DateTimeTypeInterface extends ClassTypeInterface
{

    /**
     * @return string
     */
    public function getFormat(): string;
}

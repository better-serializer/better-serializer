<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Common;

/**
 * Interface ExtensionInterface
 * @author mfris
 * @package BetterSerializer\Common
 */
interface ExtensionInterface
{

    /**
     * fully classified class name of processed class (ClassName::class) or a custom type name
     *
     * @return string
     */
    public static function getType(): string;
}

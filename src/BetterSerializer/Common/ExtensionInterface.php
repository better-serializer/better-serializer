<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Common;

use BetterSerializer\DataBind\MetaData\Type\Parameters\ParametersInterface;

/**
 * Interface ExtensionInterface
 * @author mfris
 * @package BetterSerializer\Common
 */
interface ExtensionInterface
{

    /**
     * @param ParametersInterface $parameters
     */
    public function __construct(ParametersInterface $parameters);

    /**
     * fully classified class name of processed class (ClassName::class) or a custom type name
     *
     * @return string
     */
    public static function getType(): string;
}

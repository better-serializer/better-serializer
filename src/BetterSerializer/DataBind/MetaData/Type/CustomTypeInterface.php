<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type;

use BetterSerializer\DataBind\MetaData\Type\Parameters\ParametersInterface;

/**
 * Class CustomObjectType
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type
 */
interface CustomTypeInterface extends TypeInterface
{

    /**
     * @return string
     */
    public function getCustomType(): string;

    /**
     * @return ParametersInterface
     */
    public function getParameters(): ParametersInterface;
}

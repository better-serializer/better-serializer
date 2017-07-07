<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Model\ConstructorParamModel;

use BetterSerializer\DataBind\MetaData\Type\TypeInterface;

/**
 * Class ConstructorParam
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Model\ConstructorParamModel
 */
interface ConstructorParamInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return TypeInterface
     */
    public function getType(): TypeInterface;

    /**
     * @return string
     */
    public function getPropertyName(): string;
}

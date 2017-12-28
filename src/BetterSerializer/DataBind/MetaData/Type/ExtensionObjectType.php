<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type;

/**
 * Class CustomObjectType
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type
 */
final class ExtensionObjectType extends AbstractExtensionObjectType
{

    /**
     * @return string
     */
    public function __toString(): string
    {
        return parent::__toString() . '::' . $this->getClassName() . '(' . $this->parameters . ')';
    }
}

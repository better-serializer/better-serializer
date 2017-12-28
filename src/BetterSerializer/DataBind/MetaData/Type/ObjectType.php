<?php
declare(strict_types = 1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Type;

/**
 * Class String
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type
 */
final class ObjectType extends AbstractObjectType
{

    /**
     * @return string
     */
    public function __toString(): string
    {
        return parent::__toString() . '<' . $this->className . '>';
    }
}

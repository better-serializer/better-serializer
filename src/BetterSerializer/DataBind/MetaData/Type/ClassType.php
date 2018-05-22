<?php
declare(strict_types = 1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Type;

/**
 *
 */
final class ClassType extends AbstractClassType
{

    /**
     * @return string
     */
    public function __toString(): string
    {
        return parent::__toString() . '<' . $this->className . '>';
    }
}

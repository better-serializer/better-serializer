<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType;

use BetterSerializer\Reflection\ReflectionClassInterface;

/**
 * Interface ContextStringFormTypeInterface
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type\StringFormType
 */
interface ContextStringFormTypeInterface extends StringFormTypeInterface
{

    /**
     * @return ReflectionClassInterface
     */
    public function getReflectionClass(): ReflectionClassInterface;
}

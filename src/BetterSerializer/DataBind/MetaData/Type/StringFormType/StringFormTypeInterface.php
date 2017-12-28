<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType;

/**
 * Class StringTypedPropertyContext
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 */
interface StringFormTypeInterface
{

    /**
     * @return string
     */
    public function getNamespace(): string;

    /**
     * @return string
     */
    public function getStringType(): string;

    /**
     * @return bool
     */
    public function isClass(): bool;

    /**
     * @return bool
     */
    public function isInterface(): bool;

    /**
     * @return bool
     */
    public function isClassOrInterface(): bool;
}

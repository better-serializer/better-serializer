<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parameters\ParametersInterface;

/**
 * Class CustomObjectType
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type
 */
interface ExtensionTypeInterface extends TypeInterface
{

    /**
     * @return string
     */
    public function getCustomType(): string;

    /**
     * @return ParametersInterface
     */
    public function getParameters(): ParametersInterface;

    /**
     * @return bool
     */
    public function isInterface(): bool;

    /**
     * @return bool
     */
    public function isClass(): bool;

    /**
     * @param InterfaceType $interface
     * @return bool
     */
    public function implementsInterface(InterfaceType $interface): bool;

    /**
     * @param AbstractClassType $object
     * @return bool
     */
    public function extendsClass(AbstractClassType $object): bool;
}

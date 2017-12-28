<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type;

/**
 * Interface ObjectTypeInterface
 * @package BetterSerializer\DataBind\MetaData\Type
 */
interface ObjectTypeInterface extends ComplexTypeInterface
{

    /**
     * @return string
     */
    public function getClassName(): string;

    /**
     * @param InterfaceType $interface
     * @return bool
     */
    public function implementsInterface(InterfaceType $interface): bool;

    /**
     * @param string $interfaceName
     * @return bool
     */
    public function implementsInterfaceAsString(string $interfaceName): bool;

    /**
     * @param AbstractObjectType $class
     * @return bool
     */
    public function extendsClass(AbstractObjectType $class): bool;

    /**
     * @param string $className
     * @return bool
     */
    public function extendsClassAsString(string $className): bool;
}

<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type;

/**
 *
 */
interface InterfaceTypeInterface extends ComplexTypeInterface
{

    /**
     * @return string
     */
    public function getInterfaceName(): string;

    /**
     * @param InterfaceType $interface
     * @return bool
     */
    public function implementsInterface(InterFaceType $interface): bool;

    /**
     * @param string $interfaceName
     * @return bool
     */
    public function implementsInterfaceAsString(string $interfaceName): bool;
}

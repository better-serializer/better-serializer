<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Extension\Registry;

/**
 *
 */
interface RegistryInterface
{
    /**
     * @param string $extensionClass
     */
    public function registerExtension(string $extensionClass): void;

    /**
     * @param string $typeString
     * @return bool
     */
    public function hasType(string $typeString): bool;
}

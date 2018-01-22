<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Extension\Registry;

use RuntimeException;

/**
 *
 */
interface CollectingRegistryInterface extends RegistryInterface
{
    /**
     * @return CollectionInterface[]
     * @throws RuntimeException
     */
    public function getTypeCollections(): array;
}

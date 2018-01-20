<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use RuntimeException;

/**
 *
 */
interface ExtensibleChainMemberInterface extends ChainMemberInterface
{

    /**
     * @param string $extensionClass
     * @throws RuntimeException
     */
    public function addExtensionClass(string $extensionClass): void;
}

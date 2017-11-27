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
     * @param string $customObjectHandlerClass
     * @throws RuntimeException
     */
    public function addCustomTypeHandlerClass(string $customObjectHandlerClass): void;
}

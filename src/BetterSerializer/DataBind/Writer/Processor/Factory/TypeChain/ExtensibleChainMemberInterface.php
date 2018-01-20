<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain;

/**
 *
 */
interface ExtensibleChainMemberInterface extends ChainMemberInterface
{

    /**
     * @param string $extensionClass
     */
    public function addExtensionClass(string $extensionClass): void;
}

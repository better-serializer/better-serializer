<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain;

/**
 *
 */
interface ExtensibleChainMemberInterface extends ChainMemberInterface
{

    /**
     * @param string $customHandlerClass
     */
    public function addCustomHandlerClass(string $customHandlerClass): void;
}

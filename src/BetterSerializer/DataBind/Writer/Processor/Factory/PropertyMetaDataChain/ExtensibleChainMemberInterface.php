<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain;

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

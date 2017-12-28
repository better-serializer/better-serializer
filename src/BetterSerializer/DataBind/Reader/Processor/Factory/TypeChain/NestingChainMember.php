<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain;

use BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactoryInterface;

/**
 *
 */
abstract class NestingChainMember extends ChainMember
{

    /**
     * @var ProcessorFactoryInterface
     */
    protected $processorFactory;

    /**
     * ChainMember constructor.
     * @param ProcessorFactoryInterface $processorFactory
     */
    public function __construct(ProcessorFactoryInterface $processorFactory)
    {
        $this->processorFactory = $processorFactory;
    }
}

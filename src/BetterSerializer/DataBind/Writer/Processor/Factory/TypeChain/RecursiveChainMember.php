<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain;

use BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryInterface;

/**
 * Class ChainMember
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor\Converter\Chain
 */
abstract class RecursiveChainMember extends ChainMember
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

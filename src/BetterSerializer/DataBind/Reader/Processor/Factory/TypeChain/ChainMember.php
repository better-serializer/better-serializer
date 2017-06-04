<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain;

use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactoryInterface;
use BetterSerializer\DataBind\Reader\Processor\ProcessorInterface;

/**
 * Class ChainMember
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Processor\Factory\Chain
 */
abstract class ChainMember implements ChainMemberInterface
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

    /**
     * @param TypeInterface $type
     * @return ProcessorInterface|null
     */
    public function create(TypeInterface $type): ?ProcessorInterface
    {
        if (!$this->isCreatable($type)) {
            return null;
        }

        return $this->createProcessor($type);
    }

    /**
     * @param TypeInterface $type
     * @return bool
     */
    abstract protected function isCreatable(TypeInterface $type): bool;

    /**
     * @param TypeInterface $type
     * @return ProcessorInterface
     */
    abstract protected function createProcessor(TypeInterface $type): ProcessorInterface;
}

<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain;

use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Writer\Processor\ProcessorInterface;
use BetterSerializer\DataBind\Writer\SerializationContextInterface;

/**
 * Class ChainMember
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor\Converter\Chain
 */
abstract class ChainMember implements ChainMemberInterface
{

    /**
     * @param TypeInterface $type
     * @param SerializationContextInterface $context
     * @return ProcessorInterface|null
     */
    public function create(TypeInterface $type, SerializationContextInterface $context): ?ProcessorInterface
    {
        if (!$this->isCreatable($type)) {
            return null;
        }

        return $this->createProcessor($type, $context);
    }

    /**
     * @param TypeInterface $type
     * @return bool
     */
    abstract protected function isCreatable(TypeInterface $type): bool;

    /**
     * @param TypeInterface $type
     * @param SerializationContextInterface $context
     * @return ProcessorInterface
     */
    abstract protected function createProcessor(
        TypeInterface $type,
        SerializationContextInterface $context
    ): ProcessorInterface;
}

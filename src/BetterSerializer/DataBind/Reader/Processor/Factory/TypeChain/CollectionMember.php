<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain;

use BetterSerializer\DataBind\MetaData\Type\ArrayType;
use BetterSerializer\DataBind\MetaData\Type\SimpleTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Reader\Processor\ComplexCollection as ComplexCollectionProcessor;
use BetterSerializer\DataBind\Reader\Processor\SimpleCollection as SimpleCollectionProcessor;
use BetterSerializer\DataBind\Reader\Processor\ProcessorInterface;
use LogicException;
use ReflectionException;
use RuntimeException;

/**
 * Class ObjectMember
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain
 */
final class CollectionMember extends ChainMember
{

    /**
     * @param TypeInterface $type
     * @return bool
     */
    protected function isCreatable(TypeInterface $type): bool
    {
        return $type instanceof ArrayType;
    }

    /**
     * @param TypeInterface $type
     * @return ProcessorInterface
     * @throws LogicException
     * @throws ReflectionException
     * @throws RuntimeException
     */
    protected function createProcessor(TypeInterface $type): ProcessorInterface
    {
        /* @var $type ArrayType */
        $nestedType = $type->getNestedType();

        if ($nestedType instanceof SimpleTypeInterface) {
            return new SimpleCollectionProcessor();
        }

        $nestedProcessor = $this->processorFactory->createFromType($nestedType);

        return new ComplexCollectionProcessor($nestedProcessor);
    }
}

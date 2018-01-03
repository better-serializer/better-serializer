<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain;

use BetterSerializer\DataBind\Reader\Converter\ConverterFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\ArrayType;
use BetterSerializer\DataBind\MetaData\Type\SimpleTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactoryInterface;
use BetterSerializer\DataBind\Reader\Processor\ComplexCollectionProcessor;
use BetterSerializer\DataBind\Reader\Processor\SimpleCollectionProcessor;
use BetterSerializer\DataBind\Reader\Processor\ProcessorInterface;
use LogicException;
use ReflectionException;
use RuntimeException;

/**
 *
 */
final class CollectionMember extends NestingChainMember
{

    /**
     * @var ConverterFactoryInterface
     */
    private $converterFactory;

    /**
     * CollectionMember constructor.
     * @param ConverterFactoryInterface $converterFactory
     * @param ProcessorFactoryInterface $processorFactory
     */
    public function __construct(
        ConverterFactoryInterface $converterFactory,
        ProcessorFactoryInterface $processorFactory
    ) {
        $this->converterFactory = $converterFactory;
        parent::__construct($processorFactory);
    }

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
            $converter = $this->converterFactory->newConverter($nestedType);

            return new SimpleCollectionProcessor($converter);
        }

        $nestedProcessor = $this->processorFactory->createFromType($nestedType);

        return new ComplexCollectionProcessor($nestedProcessor);
    }
}

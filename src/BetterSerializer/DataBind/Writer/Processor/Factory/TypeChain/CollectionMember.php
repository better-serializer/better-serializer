<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain;

use BetterSerializer\DataBind\Writer\Converter\ConverterFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\ArrayType;
use BetterSerializer\DataBind\MetaData\Type\SimpleTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Writer\Processor\ComplexCollection as ComplexCollectionProcessor;
use BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\SimpleCollection as SimpleCollectionProcessor;
use BetterSerializer\DataBind\Writer\Processor\ProcessorInterface;
use BetterSerializer\DataBind\Writer\SerializationContextInterface;
use LogicException;
use ReflectionException;
use RuntimeException;

/**
 * Class ObjectMember
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain
 */
final class CollectionMember extends RecursiveChainMember
{

    /**
     * @var ConverterFactoryInterface
     */
    private $converterFactory;

    /**
     * CollectionMember constructor.
     * @param ConverterFactoryInterface $converterFactory
     * @param $converterFactory
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
     * @param SerializationContextInterface $context
     * @return ProcessorInterface
     * @throws LogicException
     * @throws ReflectionException
     * @throws RuntimeException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function createProcessor(TypeInterface $type, SerializationContextInterface $context): ProcessorInterface
    {
        /* @var $type ArrayType */
        $nestedType = $type->getNestedType();

        if ($nestedType instanceof SimpleTypeInterface) {
            $converter = $this->converterFactory->newConverter($nestedType);

            return new SimpleCollectionProcessor($converter);
        }

        $nestedProcessor = $this->processorFactory->createFromType($nestedType, $context);

        return new ComplexCollectionProcessor($nestedProcessor);
    }
}

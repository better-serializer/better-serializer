<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain;

use BetterSerializer\DataBind\MetaData\Type\ClassType;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Writer\MetaData\ContextualReaderInterface;
use BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\ClassProcessor;
use BetterSerializer\DataBind\Writer\Processor\ProcessorInterface;
use BetterSerializer\DataBind\Writer\SerializationContextInterface;

/**
 *
 */
final class ClassMember extends RecursiveChainMember
{

    /**
     * @var ContextualReaderInterface
     */
    private $metadataReader;

    /**
     * ObjectMember constructor.
     * @param ProcessorFactoryInterface $processorFactory
     * @param ContextualReaderInterface $metadataReader
     */
    public function __construct(ProcessorFactoryInterface $processorFactory, ContextualReaderInterface $metadataReader)
    {
        parent::__construct($processorFactory);
        $this->metadataReader = $metadataReader;
    }

    /**
     * @param TypeInterface $type
     * @return bool
     */
    protected function isCreatable(TypeInterface $type): bool
    {
        return $type instanceof ClassType;
    }

    /**
     * @param TypeInterface $type
     * @param SerializationContextInterface $context
     * @return ProcessorInterface
     */
    protected function createProcessor(TypeInterface $type, SerializationContextInterface $context): ProcessorInterface
    {
        /* @var $type ClassType */
        $metaData = $this->metadataReader->read($type->getClassName(), $context);
        $propertiesMetaData = $metaData->getPropertiesMetadata();
        $propertyProcessors = [];

        foreach ($propertiesMetaData as $propertyMetaData) {
            $propertyProcessors[] = $this->processorFactory->createFromMetaData($propertyMetaData, $context);
        }

        return new ClassProcessor($propertyProcessors);
    }
}

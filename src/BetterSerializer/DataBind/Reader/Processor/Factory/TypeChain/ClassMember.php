<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain;

use BetterSerializer\DataBind\MetaData\Reader\ReaderInterface;
use BetterSerializer\DataBind\MetaData\Type\ClassType;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Factory\InstantiatorFactoryInterface;
use BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactoryInterface;
use BetterSerializer\DataBind\Reader\Processor\ClassProcessor;
use BetterSerializer\DataBind\Reader\Processor\ProcessorInterface;
use LogicException;
use ReflectionException;

/**
 *
 */
final class ClassMember extends NestingChainMember
{

    /**
     * @var InstantiatorFactoryInterface
     */
    private $instantiatorFactory;

    /**
     * @var ReaderInterface
     */
    private $metadataReader;

    /**
     * ObjectMember constructor.
     * @param ProcessorFactoryInterface $processorFactory
     * @param InstantiatorFactoryInterface $instantiatorFactory
     * @param ReaderInterface $metadataReader
     */
    public function __construct(
        ProcessorFactoryInterface $processorFactory,
        InstantiatorFactoryInterface $instantiatorFactory,
        ReaderInterface $metadataReader
    ) {
        parent::__construct($processorFactory);
        $this->instantiatorFactory = $instantiatorFactory;
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
     * @return ProcessorInterface
     * @throws LogicException
     * @throws ReflectionException
     */
    protected function createProcessor(TypeInterface $type): ProcessorInterface
    {
        /* @var $type ClassType */
        $metaData = $this->metadataReader->read($type->getClassName());
        $instantiatorResult = $this->instantiatorFactory->newInstantiator($metaData);
        $propertiesMetaData = $instantiatorResult->getProcessedMetaData()->getPropertiesMetadata();
        $propertyProcessors = [];

        foreach ($propertiesMetaData as $propertyMetaData) {
            $propertyProcessors[] = $this->processorFactory->createFromMetaData($propertyMetaData);
        }

        return new ClassProcessor($instantiatorResult->getInstantiator(), $propertyProcessors);
    }
}

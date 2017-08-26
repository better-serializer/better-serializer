<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain;

use BetterSerializer\DataBind\MetaData\Reader\ReaderInterface;
use BetterSerializer\DataBind\MetaData\Type\ObjectType;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\Object as ObjectProcessor;
use BetterSerializer\DataBind\Writer\Processor\ProcessorInterface;
use LogicException;
use ReflectionException;

/**
 * Class ObjectMember
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor\Converter\TypeChain
 */
final class ObjectMember extends RecursiveChainMember
{

    /**
     * @var ReaderInterface
     */
    private $metadataReader;

    /**
     * ObjectMember constructor.
     * @param ProcessorFactoryInterface $processorFactory
     * @param ReaderInterface $metadataReader
     */
    public function __construct(ProcessorFactoryInterface $processorFactory, ReaderInterface $metadataReader)
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
        return $type instanceof ObjectType;
    }

    /**
     * @param TypeInterface $type
     * @return ProcessorInterface
     * @throws LogicException
     * @throws ReflectionException
     */
    protected function createProcessor(TypeInterface $type): ProcessorInterface
    {
        /* @var $type ObjectType */
        $metaData = $this->metadataReader->read($type->getClassName());
        $propertiesMetaData = $metaData->getPropertiesMetadata();
        $propertyProcessors = [];

        foreach ($propertiesMetaData as $propertyMetaData) {
            $propertyProcessors[] = $this->processorFactory->createFromMetaData($propertyMetaData);
        }

        return new ObjectProcessor($propertyProcessors);
    }
}

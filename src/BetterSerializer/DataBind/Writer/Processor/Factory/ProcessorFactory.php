<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory;

use BetterSerializer\DataBind\MetaData\ObjectPropertyMetadataInterface;
use BetterSerializer\DataBind\MetaData\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Reader\ReaderInterface;
use BetterSerializer\DataBind\Writer\Extractor\Property\Factory\AbstractFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\ProcessorInterface;
use BetterSerializer\DataBind\Writer\Processor\Object as ObjectProcessor;
use BetterSerializer\DataBind\Writer\Processor\ObjectProperty as ObjectPropertyProcessor;
use BetterSerializer\DataBind\Writer\Processor\Property as PropertyProcessor;
use BetterSerializer\DataBind\Writer\ValueWriter\Property as PropertyValueWriter;
use LogicException;
use ReflectionException;
use RuntimeException;

/**
 * Class ProcessorFactory
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor\Factory
 */
final class ProcessorFactory implements ProcessorFactoryInterface
{

    /**
     * @var ReaderInterface
     */
    private $metadataReader;

    /**
     * @var AbstractFactoryInterface
     */
    private $extractorFactory;

    /**
     * ProcessorFactory constructor.
     * @param ReaderInterface $metadataReader
     * @param AbstractFactoryInterface $extractorFactory
     */
    public function __construct(ReaderInterface $metadataReader, AbstractFactoryInterface $extractorFactory)
    {
        $this->metadataReader = $metadataReader;
        $this->extractorFactory = $extractorFactory;
    }

    /**
     * @param string $type
     * @return ProcessorInterface
     * @throws ReflectionException
     * @throws LogicException
     * @throws RuntimeException
     */
    public function create(string $type): ProcessorInterface
    {
        return $this->createObjectProcessor($type);
    }

    /**
     * @param string $className
     * @return ObjectProcessor
     * @throws ReflectionException
     * @throws LogicException
     * @throws RuntimeException
     */
    private function createObjectProcessor(string $className): ObjectProcessor
    {
        $metaData = $this->metadataReader->read($className);
        $propertiesMetaData = $metaData->getPropertiesMetadata();
        $propertyProcessors = [];

        foreach ($propertiesMetaData as $propertyMetaData) {
            $propertyProcessors[] = $this->createPropertyProcessor($propertyMetaData);
        }

        return new ObjectProcessor($propertyProcessors);
    }

    /**
     * @param PropertyMetaDataInterface $propertyMetaData
     * @return ProcessorInterface
     * @throws RuntimeException
     */
    private function createPropertyProcessor(PropertyMetaDataInterface $propertyMetaData): ProcessorInterface
    {
        if ($propertyMetaData instanceof ObjectPropertyMetadataInterface) {
            return $this->createObjectPropertyProcessor($propertyMetaData);
        }

        return $this->createSimplePropertyProcessor($propertyMetaData);
    }

    /**
     * @param PropertyMetaDataInterface $propertyMetaData
     * @return PropertyProcessor
     */
    private function createSimplePropertyProcessor(PropertyMetaDataInterface $propertyMetaData): PropertyProcessor
    {
        $extractor = $this->extractorFactory->newExtractor($propertyMetaData);
        $valueWriter = new PropertyValueWriter($propertyMetaData->getOutputKey());

        return new PropertyProcessor($extractor, $valueWriter);
    }

    /**
     * @param ObjectPropertyMetadataInterface $propertyMetaData
     * @return ObjectPropertyProcessor
     */
    private function createObjectPropertyProcessor(
        ObjectPropertyMetadataInterface $propertyMetaData
    ): ObjectPropertyProcessor {
        $extractor = $this->extractorFactory->newExtractor($propertyMetaData);
        $objectProcessor = $this->createObjectProcessor($propertyMetaData->getObjectClass());

        return new ObjectPropertyProcessor($extractor, $objectProcessor, $propertyMetaData->getOutputKey());
    }
}

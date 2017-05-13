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
final class ProcessorFactory
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
     * @param string $outputKey
     * @return ProcessorInterface
     * @throws ReflectionException
     * @throws LogicException
     * @throws RuntimeException
     */
    private function createObjectProcessor(
        string $className,
        string $outputKey = ''
    ): ProcessorInterface {
        $metaData = $this->metadataReader->read($className);
        $propertiesMetadata = $metaData->getPropertiesMetadata();
        $propertyProcessors = [];

        foreach ($propertiesMetadata as $propertyMetadata) {
            if ($propertyMetadata instanceof ObjectPropertyMetadataInterface) {
                $propertyProcessors[] = $this->createObjectProcessor(
                    $propertyMetadata->getObjectClass(),
                    $propertyMetadata->getOutputKey()
                );
                continue;
            }

            $propertyProcessors[] = $this->createPropertyProcessor($propertyMetadata);
        }

        return new ObjectProcessor($propertyProcessors, $outputKey);
    }

    /**
     * @param PropertyMetaDataInterface $propertyMetaData
     * @return ProcessorInterface
     * @throws RuntimeException
     */
    private function createPropertyProcessor(PropertyMetaDataInterface $propertyMetaData): ProcessorInterface
    {
        $extractor = $this->extractorFactory->newExtractor($propertyMetaData);
        $valueWriter = new PropertyValueWriter($propertyMetaData->getOutputKey());

        return new PropertyProcessor($extractor, $valueWriter);
    }
}

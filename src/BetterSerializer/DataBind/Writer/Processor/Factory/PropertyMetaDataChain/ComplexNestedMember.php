<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain;

use BetterSerializer\DataBind\MetaData\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\ArrayType;
use BetterSerializer\DataBind\MetaData\Type\ObjectType;
use BetterSerializer\DataBind\Writer\Extractor\Factory\AbstractFactoryInterface as ExtractorFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\ComplexNestedProcessorInterface;
use BetterSerializer\DataBind\Writer\Processor\ComplexNested;
use BetterSerializer\DataBind\Writer\Processor\ProcessorInterface;
use LogicException;
use ReflectionException;
use RuntimeException;

/**
 * Class SimpleMember
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain
 */
final class ComplexNestedMember extends ExtractingChainMember
{

    /**
     * @var ProcessorFactoryInterface
     */
    private $processorFactory;

    /**
     * ObjectMember constructor.
     * @param ProcessorFactoryInterface $processorFactory
     * @param ExtractorFactoryInterface $extractorFactory
     */
    public function __construct(
        ProcessorFactoryInterface $processorFactory,
        ExtractorFactoryInterface $extractorFactory
    ) {
        parent::__construct($extractorFactory);
        $this->processorFactory = $processorFactory;
    }

    /**
     * @param PropertyMetaDataInterface $metaData
     * @return bool
     */
    protected function isCreatable(PropertyMetaDataInterface $metaData): bool
    {
        $type = $metaData->getType();

        return $type instanceof ObjectType || $type instanceof ArrayType;
    }

    /**
     * @param PropertyMetaDataInterface $metaData
     * @return ProcessorInterface
     * @throws LogicException
     * @throws ReflectionException
     * @throws RuntimeException
     */
    protected function createProcessor(PropertyMetaDataInterface $metaData): ProcessorInterface
    {
        $extractor = $this->extractorFactory->newExtractor($metaData);
        $objectProcessor = $this->processorFactory->createFromType($metaData->getType());

        if (!$objectProcessor instanceof ComplexNestedProcessorInterface) {
            throw new LogicException("Invalid processor type: '" . get_class($objectProcessor) . "'");
        }

        return new ComplexNested($extractor, $objectProcessor, $metaData->getOutputKey());
    }
}
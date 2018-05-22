<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\ArrayType;
use BetterSerializer\DataBind\MetaData\Type\ExtensionTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\ClassType;
use BetterSerializer\DataBind\Naming\PropertyNameTranslator\TranslatorInterface;
use BetterSerializer\DataBind\Writer\Extractor\Factory\AbstractFactoryInterface as ExtractorFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\ComplexPropertyProcessor;
use BetterSerializer\DataBind\Writer\Processor\ProcessorInterface;
use BetterSerializer\DataBind\Writer\SerializationContextInterface;
use LogicException;
use ReflectionException;
use RuntimeException;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
final class ComplexPropertyMember extends ExtractingChainMember
{

    /**
     * @var ProcessorFactoryInterface
     */
    private $processorFactory;

    /**
     * @param ProcessorFactoryInterface $processorFactory
     * @param ExtractorFactoryInterface $extractorFactory
     * @param TranslatorInterface $nameTranslator
     */
    public function __construct(
        ProcessorFactoryInterface $processorFactory,
        ExtractorFactoryInterface $extractorFactory,
        TranslatorInterface $nameTranslator
    ) {
        parent::__construct($extractorFactory, $nameTranslator);
        $this->processorFactory = $processorFactory;
    }

    /**
     * @param PropertyMetaDataInterface $metaData
     * @return bool
     */
    protected function isCreatable(PropertyMetaDataInterface $metaData): bool
    {
        $type = $metaData->getType();

        return $type instanceof ClassType ||
               $type instanceof ArrayType ||
               $type instanceof ExtensionTypeInterface;
    }

    /**
     * @param PropertyMetaDataInterface $metaData
     * @param SerializationContextInterface $context
     * @return ProcessorInterface
     * @throws LogicException
     * @throws ReflectionException
     * @throws RuntimeException
     */
    protected function createProcessor(
        PropertyMetaDataInterface $metaData,
        SerializationContextInterface $context
    ): ProcessorInterface {
        $extractor = $this->extractorFactory->newExtractor($metaData);
        $nestedProcessor = $this->processorFactory->createFromType($metaData->getType(), $context);
        $serializationName = $this->nameTranslator->translate($metaData);

        return new ComplexPropertyProcessor($extractor, $nestedProcessor, $serializationName);
    }
}

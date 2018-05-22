<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain;

use BetterSerializer\DataBind\MetaData\Type\DateTimeTypeInterface;
use BetterSerializer\DataBind\Naming\PropertyNameTranslator\TranslatorInterface;
use BetterSerializer\DataBind\Writer\Converter\ConverterFactoryInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\SimpleTypeInterface;
use BetterSerializer\DataBind\Writer\Extractor\Factory\AbstractFactoryInterface as ExtractorFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\ProcessorInterface;
use BetterSerializer\DataBind\Writer\Processor\SimplePropertyProcessor;
use BetterSerializer\DataBind\Writer\SerializationContextInterface;

/**
 *
 */
final class SimplePropertyMember extends ExtractingChainMember
{

    /**
     * @var ConverterFactoryInterface
     */
    private $converterFactory;

    /**
     * @param ConverterFactoryInterface $converterFactory
     * @param ExtractorFactoryInterface $extractorFactory
     * @param TranslatorInterface $nameTranslator
     */
    public function __construct(
        ConverterFactoryInterface $converterFactory,
        ExtractorFactoryInterface $extractorFactory,
        TranslatorInterface $nameTranslator
    ) {
        $this->converterFactory = $converterFactory;
        parent::__construct($extractorFactory, $nameTranslator);
    }

    /**
     * @param PropertyMetaDataInterface $metaData
     * @return bool
     */
    protected function isCreatable(PropertyMetaDataInterface $metaData): bool
    {
        $type = $metaData->getType();

        return $type instanceof SimpleTypeInterface || $type instanceof DateTimeTypeInterface;
    }

    /**
     * @param PropertyMetaDataInterface $metaData
     * @param SerializationContextInterface $context
     * @return ProcessorInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function createProcessor(
        PropertyMetaDataInterface $metaData,
        SerializationContextInterface $context
    ): ProcessorInterface {
        $converter = $this->converterFactory->newConverter($metaData->getType());
        $extractor = $this->extractorFactory->newExtractor($metaData);
        $serializationName = $this->nameTranslator->translate($metaData);

        return new SimplePropertyProcessor($extractor, $converter, $serializationName);
    }
}

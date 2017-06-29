<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain;

use BetterSerializer\DataBind\Converter\Factory\ConverterFactoryInterface;
use BetterSerializer\DataBind\MetaData\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\SimpleTypeInterface;
use BetterSerializer\DataBind\Writer\Extractor\Factory\AbstractFactoryInterface as ExtractorFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\ProcessorInterface;
use BetterSerializer\DataBind\Writer\Processor\Property;

/**
 * Class SimpleMember
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain
 */
final class SimpleMember extends ExtractingChainMember
{

    /**
     * @var ConverterFactoryInterface
     */
    private $converterFactory;

    /**
     * SimpleMember constructor.
     * @param ConverterFactoryInterface $converterFactory
     * @param ExtractorFactoryInterface $extractorFactory
     */
    public function __construct(
        ConverterFactoryInterface $converterFactory,
        ExtractorFactoryInterface $extractorFactory
    ) {
        $this->converterFactory = $converterFactory;
        parent::__construct($extractorFactory);
    }

    /**
     * @param PropertyMetaDataInterface $metaData
     * @return bool
     */
    protected function isCreatable(PropertyMetaDataInterface $metaData): bool
    {
        return $metaData->getType() instanceof SimpleTypeInterface;
    }

    /**
     * @param PropertyMetaDataInterface $metaData
     * @return ProcessorInterface
     */
    protected function createProcessor(PropertyMetaDataInterface $metaData): ProcessorInterface
    {
        $converter = $this->converterFactory->newConverter($metaData->getType());
        $extractor = $this->extractorFactory->newExtractor($metaData);

        return new Property($extractor, $converter, $metaData->getOutputKey());
    }
}

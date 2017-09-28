<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory;

use BetterSerializer\DataBind\Writer\Converter\ConverterFactoryInterface;
use BetterSerializer\DataBind\Writer\Extractor\Factory\AbstractFactoryInterface as ExtractorFactoryInterface;
use BetterSerializer\DataBind\Writer\MetaData\ContextualReaderInterface;
use BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain\ComplexNestedMember;
use BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain\SimpleMember;
use BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain\CollectionMember;
use BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain\ObjectMember;

/**
 * Class ProcessorFactoryBuilder
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor\Factory
 */
final class ProcessorFactoryBuilder
{

    /**
     * @var ConverterFactoryInterface
     */
    private $converterFactory;

    /**
     * @var ExtractorFactoryInterface
     */
    private $extractorFactory;

    /**
     * @var ContextualReaderInterface
     */
    private $metaDataReader;

    /**
     * ProcessorFactoryBuilder constructor.
     * @param ConverterFactoryInterface $converterFactory
     * @param ExtractorFactoryInterface $extractorFactory
     * @param ContextualReaderInterface $metaDataReader
     */
    public function __construct(
        ConverterFactoryInterface $converterFactory,
        ExtractorFactoryInterface $extractorFactory,
        ContextualReaderInterface $metaDataReader
    ) {
        $this->converterFactory = $converterFactory;
        $this->extractorFactory = $extractorFactory;
        $this->metaDataReader = $metaDataReader;
    }

    /**
     * @return ProcessorFactoryInterface
     */
    public function build(): ProcessorFactoryInterface
    {
        $factory = new RecursiveProcessorFactory(new ProcessorFactory());
        $metaDataObject = new ComplexNestedMember($factory, $this->extractorFactory);
        $metaDataSimple = new SimpleMember($this->converterFactory, $this->extractorFactory);
        $typeArrayMember = new CollectionMember($this->converterFactory, $factory);
        $objectMember = new Objectmember($factory, $this->metaDataReader);

        $factory->addMetaDataChainMember($metaDataSimple);
        $factory->addMetaDataChainMember($metaDataObject);
        $factory->addTypeChainMember($typeArrayMember);
        $factory->addTypeChainMember($objectMember);

        return $factory;
    }
}

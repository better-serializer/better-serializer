<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory;

use BetterSerializer\DataBind\MetaData\Reader\ReaderInterface;
use BetterSerializer\DataBind\Writer\Extractor\Factory\AbstractFactoryInterface as ExtractorFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain\ComplexNestedMember;
use BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain\SimpleMember;
use BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain\CollectionMember;
use BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain\ObjectMember as TypeObjectmember;

/**
 * Class ProcessorFactoryBuilder
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor\Factory
 */
final class ProcessorFactoryBuilder
{

    /**
     * @var ExtractorFactoryInterface
     */
    private $extractorFactory;

    /**
     * @var ReaderInterface
     */
    private $metaDataReader;

    /**
     * ProcessorFactoryBuilder constructor.
     * @param ExtractorFactoryInterface $extractorFactory
     * @param ReaderInterface $metaDataReader
     */
    public function __construct(ExtractorFactoryInterface $extractorFactory, ReaderInterface $metaDataReader)
    {
        $this->extractorFactory = $extractorFactory;
        $this->metaDataReader = $metaDataReader;
    }

    /**
     * @return ProcessorFactory
     */
    public function build(): ProcessorFactory
    {
        $factory = new ProcessorFactory();
        $metaDataObject = new ComplexNestedMember($factory, $this->extractorFactory);
        $metaDataSimple = new SimpleMember($this->extractorFactory);
        $typeArrayMember = new CollectionMember($factory);
        $typeObjectMember = new TypeObjectmember($factory, $this->metaDataReader);

        $factory->addMetaDataChainMember($metaDataSimple);
        $factory->addMetaDataChainMember($metaDataObject);
        $factory->addTypeChainMember($typeArrayMember);
        $factory->addTypeChainMember($typeObjectMember);

        return $factory;
    }
}

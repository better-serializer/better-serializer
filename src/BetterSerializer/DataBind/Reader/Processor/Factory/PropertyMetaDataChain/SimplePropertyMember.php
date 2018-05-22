<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory\PropertyMetaDataChain;

use BetterSerializer\DataBind\Naming\PropertyNameTranslator\TranslatorInterface;
use BetterSerializer\DataBind\Reader\Converter\ConverterFactoryInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\DateTimeTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\SimpleTypeInterface;
use BetterSerializer\DataBind\Reader\Injector\Factory\AbstractFactoryInterface as InjectorFactoryInterface;
use BetterSerializer\DataBind\Reader\Processor\ProcessorInterface;
use BetterSerializer\DataBind\Reader\Processor\SimplePropertyProcessor;

/**
 *
 */
final class SimplePropertyMember extends InjectingChainMember
{

    /**
     * @var ConverterFactoryInterface
     */
    private $converterFactory;

    /**
     * @param ConverterFactoryInterface $converterFactory
     * @param InjectorFactoryInterface $injectorFactory
     * @param TranslatorInterface $nameTranslator
     */
    public function __construct(
        ConverterFactoryInterface $converterFactory,
        InjectorFactoryInterface $injectorFactory,
        TranslatorInterface $nameTranslator
    ) {
        $this->converterFactory = $converterFactory;
        parent::__construct($injectorFactory, $nameTranslator);
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
     * @return ProcessorInterface
     */
    protected function createProcessor(PropertyMetaDataInterface $metaData): ProcessorInterface
    {
        $injector = $this->injectorFactory->newInjector($metaData);
        $converter = $this->converterFactory->newConverter($metaData->getType());
        $serializationName = $this->nameTranslator->translate($metaData);

        return new SimplePropertyProcessor($injector, $converter, $serializationName);
    }
}

<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory\PropertyMetaDataChain;

use BetterSerializer\DataBind\Reader\Converter\ConverterFactoryInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\DateTimeTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\SimpleTypeInterface;
use BetterSerializer\DataBind\Reader\Injector\Factory\AbstractFactoryInterface as InjectorFactoryInterface;
use BetterSerializer\DataBind\Reader\Processor\ProcessorInterface;
use BetterSerializer\DataBind\Reader\Processor\Property;

/**
 * Class SimpleMember
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Processor\Converter\PropertyMetaDataChain
 */
final class SimpleMember extends InjectingChainMember
{

    /**
     * @var ConverterFactoryInterface
     */
    private $converterFactory;

    /**
     * SimpleMember constructor.
     * @param ConverterFactoryInterface $converterFactory
     * @param InjectorFactoryInterface $injectorFactory
     */
    public function __construct(ConverterFactoryInterface $converterFactory, InjectorFactoryInterface $injectorFactory)
    {
        $this->converterFactory = $converterFactory;
        parent::__construct($injectorFactory);
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

        return new Property($injector, $converter, $metaData->getOutputKey());
    }
}

<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory;

use BetterSerializer\DataBind\Converter\Factory\ConverterFactoryInterface;
use BetterSerializer\DataBind\MetaData\Reader\ReaderInterface;
use BetterSerializer\DataBind\Reader\Injector\Factory\AbstractFactoryInterface as InjectorFactoryInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Factory\Deserialize\DeserializeInstantiatorFactory;
use BetterSerializer\DataBind\Reader\Instantiator\Factory\InstantiatorFactory;
use BetterSerializer\DataBind\Reader\Instantiator\Factory\Standard\ParamProcessor\Chain\ComplexParamProcessorFactory;
use BetterSerializer\DataBind\Reader\Instantiator\Factory\Standard\ParamProcessor\Chain\SimpleParamProcessorFactory;
use BetterSerializer\DataBind\Reader\Instantiator\Factory\Standard\ParamProcessor\ParamProcessorFactory;
use BetterSerializer\DataBind\Reader\Instantiator\Factory\Standard\StandardInstantiatorFactory;
use BetterSerializer\DataBind\Reader\Processor\Factory\PropertyMetaDataChain\ComplexNestedMember;
use BetterSerializer\DataBind\Reader\Processor\Factory\PropertyMetaDataChain\SimpleMember;
use BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain\CollectionMember;
use BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain\ObjectMember;
use LogicException;

/**
 * Class ProcessorFactoryBuilder
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Processor\Factory
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
final class ProcessorFactoryBuilder
{
    /**
     * @var ConverterFactoryInterface
     */
    private $converterFactory;

    /**
     * @var InjectorFactoryInterface
     */
    private $injectorFactory;

    /**
     * @var ReaderInterface
     */
    private $metaDataReader;

    /**
     * ProcessorFactoryBuilder constructor.
     * @param ConverterFactoryInterface $converterFactory
     * @param InjectorFactoryInterface $injectorFactory
     * @param ReaderInterface $metaDataReader
     */
    public function __construct(
        ConverterFactoryInterface $converterFactory,
        InjectorFactoryInterface $injectorFactory,
        ReaderInterface $metaDataReader
    ) {
        $this->injectorFactory = $injectorFactory;
        $this->converterFactory = $converterFactory;
        $this->metaDataReader = $metaDataReader;
    }

    /**
     * @return ProcessorFactory
     * @throws LogicException
     */
    public function build(): ProcessorFactory
    {
        $factory = new ProcessorFactory();
        $metaDataObject = new ComplexNestedMember($factory, $this->injectorFactory);
        $metaDataSimple = new SimpleMember($this->converterFactory, $this->injectorFactory);
        $typeArrayMember = new CollectionMember($this->converterFactory, $factory);

        $paramProcFactory = new ParamProcessorFactory([
            new SimpleParamProcessorFactory(),
            new ComplexParamProcessorFactory($factory),
        ]);

        $instantiatorFactory = new InstantiatorFactory([
            new StandardInstantiatorFactory($paramProcFactory),
            new DeserializeInstantiatorFactory(),
        ]);

        $objectMember = new Objectmember($factory, $instantiatorFactory, $this->metaDataReader);

        $factory->addMetaDataChainMember($metaDataSimple);
        $factory->addMetaDataChainMember($metaDataObject);
        $factory->addTypeChainMember($typeArrayMember);
        $factory->addTypeChainMember($objectMember);

        return $factory;
    }
}

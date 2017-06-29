<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory;

use BetterSerializer\DataBind\Converter\Factory\ConverterFactoryInterface;
use BetterSerializer\DataBind\MetaData\Reader\ReaderInterface;
use BetterSerializer\DataBind\Reader\Constructor\Factory\ConstructorFactoryInterface;
use BetterSerializer\DataBind\Reader\Injector\Factory\AbstractFactoryInterface as InjectorFactoryInterface;
use BetterSerializer\DataBind\Reader\Processor\Factory\PropertyMetaDataChain\ComplexNestedMember;
use BetterSerializer\DataBind\Reader\Processor\Factory\PropertyMetaDataChain\SimpleMember;
use BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain\CollectionMember;
use BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain\ObjectMember;

/**
 * Class ProcessorFactoryBuilder
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Processor\Factory
 */
final class ProcessorFactoryBuilder
{

    /**
     * @var ConstructorFactoryInterface
     */
    private $constructorFactory;

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
     * @param ConstructorFactoryInterface $constructorFactory
     * @param ConverterFactoryInterface $converterFactory
     * @param InjectorFactoryInterface $injectorFactory
     * @param ReaderInterface $metaDataReader
     */
    public function __construct(
        ConstructorFactoryInterface $constructorFactory,
        ConverterFactoryInterface $converterFactory,
        InjectorFactoryInterface $injectorFactory,
        ReaderInterface $metaDataReader
    ) {
        $this->constructorFactory = $constructorFactory;
        $this->injectorFactory = $injectorFactory;
        $this->converterFactory = $converterFactory;
        $this->metaDataReader = $metaDataReader;
    }

    /**
     * @return ProcessorFactory
     */
    public function build(): ProcessorFactory
    {
        $factory = new ProcessorFactory();
        $metaDataObject = new ComplexNestedMember($factory, $this->injectorFactory);
        $metaDataSimple = new SimpleMember($this->converterFactory, $this->injectorFactory);
        $typeArrayMember = new CollectionMember($this->converterFactory, $factory);
        $objectMember = new Objectmember($factory, $this->constructorFactory, $this->metaDataReader);

        $factory->addMetaDataChainMember($metaDataSimple);
        $factory->addMetaDataChainMember($metaDataObject);
        $factory->addTypeChainMember($typeArrayMember);
        $factory->addTypeChainMember($objectMember);

        return $factory;
    }
}

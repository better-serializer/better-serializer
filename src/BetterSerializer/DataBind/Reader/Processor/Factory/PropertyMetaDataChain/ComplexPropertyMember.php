<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory\PropertyMetaDataChain;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\ArrayType;
use BetterSerializer\DataBind\MetaData\Type\ExtensionTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\ObjectType;
use BetterSerializer\DataBind\Reader\Injector\Factory\AbstractFactoryInterface as InjectorFactoryInterface;
use BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactoryInterface;
use BetterSerializer\DataBind\Reader\Processor\PropertyProcessorInterface;
use BetterSerializer\DataBind\Reader\Processor\ComplexProperty;
use BetterSerializer\DataBind\Reader\Processor\ProcessorInterface;
use LogicException;
use ReflectionException;
use RuntimeException;

/**
 * Class SimpleMember
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Processor\Factory\PropertyMetaDataChain
 */
final class ComplexPropertyMember extends InjectingChainMember
{

    /**
     * @var ProcessorFactoryInterface
     */
    private $processorFactory;

    /**
     * ObjectMember constructor.
     * @param ProcessorFactoryInterface $processorFactory
     * @param InjectorFactoryInterface $injectorFactory
     */
    public function __construct(
        ProcessorFactoryInterface $processorFactory,
        InjectorFactoryInterface $injectorFactory
    ) {
        parent::__construct($injectorFactory);
        $this->processorFactory = $processorFactory;
    }

    /**
     * @param PropertyMetaDataInterface $metaData
     * @return bool
     */
    protected function isCreatable(PropertyMetaDataInterface $metaData): bool
    {
        $type = $metaData->getType();

        return $type instanceof ObjectType || $type instanceof ArrayType || $type instanceof ExtensionTypeInterface;
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
        $injector = $this->injectorFactory->newInjector($metaData);
        $nestedProcessor = $this->processorFactory->createFromType($metaData->getType());

        return new ComplexProperty($injector, $nestedProcessor, $metaData->getOutputKey());
    }
}

<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory\PropertyMetaDataChain;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\ArrayType;
use BetterSerializer\DataBind\MetaData\Type\ObjectType;
use BetterSerializer\DataBind\Reader\Injector\Factory\AbstractFactoryInterface as InjectorFactoryInterface;
use BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactoryInterface;
use BetterSerializer\DataBind\Reader\Processor\ComplexNestedProcessorInterface;
use BetterSerializer\DataBind\Reader\Processor\ComplexNested;
use BetterSerializer\DataBind\Reader\Processor\ProcessorInterface;
use LogicException;
use ReflectionException;
use RuntimeException;

/**
 * Class SimpleMember
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Processor\Factory\PropertyMetaDataChain
 */
final class ComplexNestedMember extends InjectingChainMember
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

        return $type instanceof ObjectType || $type instanceof ArrayType;
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
        $objectProcessor = $this->processorFactory->createFromType($metaData->getType());

        if (!$objectProcessor instanceof ComplexNestedProcessorInterface) {
            throw new LogicException("Invalid processor type: '" . get_class($objectProcessor) . "'");
        }

        return new ComplexNested($injector, $objectProcessor, $metaData->getOutputKey());
    }
}

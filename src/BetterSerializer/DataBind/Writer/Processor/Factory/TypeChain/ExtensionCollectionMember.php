<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain;

use BetterSerializer\DataBind\MetaData\Type\ExtensionCollectionTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Writer\Processor\ExtensionCollectionProcessor;
use BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\ProcessorInterface;
use BetterSerializer\DataBind\Writer\SerializationContextInterface;
use LogicException;
use ReflectionException;
use RuntimeException;

/**
 *
 */
final class ExtensionCollectionMember extends AbstractExtensionMember
{

    /**
     * @var ProcessorFactoryInterface
     */
    private $processorFactory;

    /**
     * @param ProcessorFactoryInterface $processorFactory
     * @param array $customObjectClasses
     * @throws RuntimeException
     */
    public function __construct(ProcessorFactoryInterface $processorFactory, array $customObjectClasses = [])
    {
        $this->processorFactory = $processorFactory;
        parent::__construct($customObjectClasses);
    }


    /**
     * @param TypeInterface $type
     * @return bool
     */
    protected function isCreatable(TypeInterface $type): bool
    {
        return $type instanceof ExtensionCollectionTypeInterface
            && isset($this->extensionClasses[$type->getCustomType()]);
    }

    /**
     * @param TypeInterface $type
     * @param SerializationContextInterface $context
     * @return ProcessorInterface
     * @throws LogicException
     * @throws ReflectionException
     * @throws RuntimeException
     */
    protected function createProcessor(
        TypeInterface $type,
        SerializationContextInterface $context
    ): ProcessorInterface {
        /* @var $type ExtensionCollectionTypeInterface */
        $customCollectionType = $this->extensionClasses[$type->getCustomType()];
        $processor = $this->processorFactory->createFromType($type->getNestedType(), $context);
        $extension = new $customCollectionType($type->getParameters());

        return new ExtensionCollectionProcessor($processor, $extension);
    }
}

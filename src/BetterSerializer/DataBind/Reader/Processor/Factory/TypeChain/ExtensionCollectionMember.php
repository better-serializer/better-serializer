<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain;

use BetterSerializer\DataBind\MetaData\Type\ExtensionCollectionTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Reader\Processor\ExtensionCollection as ExtensionCollectionProcessor;
use BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactoryInterface;
use BetterSerializer\DataBind\Reader\Processor\ProcessorInterface;
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
            && isset($this->customHandlerClasses[$type->getCustomType()]);
    }

    /**
     * @param TypeInterface $type
     * @return ProcessorInterface
     * @throws LogicException
     * @throws ReflectionException
     * @throws RuntimeException
     */
    protected function createProcessor(TypeInterface $type): ProcessorInterface
    {
        /* @var $type ExtensionCollectionTypeInterface */
        $customCollectionType = $this->customHandlerClasses[$type->getCustomType()];
        $processor = $this->processorFactory->createFromType($type->getNestedType());
        $extension = new $customCollectionType($type->getParameters());

        return new ExtensionCollectionProcessor($processor, $extension);
    }
}

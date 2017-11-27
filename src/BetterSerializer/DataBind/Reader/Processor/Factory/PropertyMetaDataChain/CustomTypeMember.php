<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory\PropertyMetaDataChain;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\CustomTypeInterface;
use BetterSerializer\DataBind\Reader\Injector\Factory\AbstractFactoryInterface as InjectorFactoryInterface;
use BetterSerializer\DataBind\Reader\Processor\CustomType as CustomTypeProcessor;
use BetterSerializer\DataBind\Reader\Processor\ProcessorInterface;
use RuntimeException;

/**
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Processor\Converter\TypeChain
 */
final class CustomTypeMember extends InjectingChainMember implements ExtensibleChainMemberInterface
{

    /**
     * @var string[]
     */
    private $customHandlerClasses;

    /**
     * @param InjectorFactoryInterface $injectorFactory
     * @param string[] $customObjectClasses
     * @throws RuntimeException
     */
    public function __construct(InjectorFactoryInterface $injectorFactory, array $customObjectClasses = [])
    {
        parent::__construct($injectorFactory);

        foreach ($customObjectClasses as $customObjectClass) {
            $this->addCustomHandlerClass($customObjectClass);
        }
    }

    /**
     * @param string $customHandlerClass
     * @throws RuntimeException
     */
    public function addCustomHandlerClass(string $customHandlerClass): void
    {
        if (!method_exists($customHandlerClass, 'getType')) {
            throw new RuntimeException(
                sprintf('Type handler %s is missing the getType method.', $customHandlerClass)
            );
        }

        $customType = call_user_func("{$customHandlerClass}::getType");

        if (isset($this->customHandlerClasses[$customType])) {
            throw new RuntimeException(sprintf('Handler for class %s is already registered.', $customType));
        }

        $this->customHandlerClasses[$customType] = $customHandlerClass;
    }

    /**
     * @param PropertyMetaDataInterface $metaData $type
     * @return bool
     */
    protected function isCreatable(PropertyMetaDataInterface $metaData): bool
    {
        $type = $metaData->getType();

        return $type instanceof CustomTypeInterface && isset($this->customHandlerClasses[$type->getCustomType()]);
    }

    /**
     * @param PropertyMetaDataInterface $metaData
     * @return ProcessorInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function createProcessor(PropertyMetaDataInterface $metaData): ProcessorInterface
    {
        $type = $metaData->getType();
        /* @var $type CustomTypeInterface */
        $injector = $this->injectorFactory->newInjector($metaData);
        $customType = $this->customHandlerClasses[$type->getCustomType()];
        $handler = new $customType($type->getParameters());

        return new CustomTypeProcessor($injector, $handler, $metaData->getOutputKey());
    }
}

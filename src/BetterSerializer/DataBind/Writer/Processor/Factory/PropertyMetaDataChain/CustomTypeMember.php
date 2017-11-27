<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\CustomTypeInterface;
use BetterSerializer\DataBind\Writer\Processor\CustomType as CustomTypeProcessor;
use BetterSerializer\DataBind\Writer\Processor\ProcessorInterface;
use BetterSerializer\DataBind\Writer\SerializationContextInterface;
use RuntimeException;

/**
 *
 */
final class CustomTypeMember extends ChainMember implements ExtensibleChainMemberInterface
{

    /**
     * @var string[]
     */
    private $customHandlerClasses;

    /**
     * @param string[] $customObjectClasses
     * @throws RuntimeException
     */
    public function __construct(array $customObjectClasses = [])
    {
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
     * @param SerializationContextInterface $context
     * @return ProcessorInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function createProcessor(
        PropertyMetaDataInterface $metaData,
        SerializationContextInterface $context
    ): ProcessorInterface {
        $type = $metaData->getType();
        /* @var $type CustomTypeInterface */
        $customType = $this->customHandlerClasses[$type->getCustomType()];
        $handler = new $customType($type->getParameters());

        return new CustomTypeProcessor($handler, $metaData->getOutputKey());
    }
}

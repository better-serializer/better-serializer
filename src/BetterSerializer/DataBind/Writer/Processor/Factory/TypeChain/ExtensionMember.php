<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain;

use BetterSerializer\DataBind\MetaData\Type\ExtensionTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Writer\Processor\ExtensionProcessor;
use BetterSerializer\DataBind\Writer\Processor\ProcessorInterface;
use BetterSerializer\DataBind\Writer\SerializationContextInterface;

/**
 *
 */
final class ExtensionMember extends AbstractExtensionMember
{

    /**
     * @param TypeInterface $type
     * @return bool
     */
    protected function isCreatable(TypeInterface $type): bool
    {
        return $type instanceof ExtensionTypeInterface && isset($this->customHandlerClasses[$type->getCustomType()]);
    }

    /**
     * @param TypeInterface $type
     * @param SerializationContextInterface $context
     * @return ProcessorInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function createProcessor(
        TypeInterface $type,
        SerializationContextInterface $context
    ): ProcessorInterface {
        /* @var $type ExtensionTypeInterface */
        $customType = $this->customHandlerClasses[$type->getCustomType()];
        $handler = new $customType($type->getParameters());

        return new ExtensionProcessor($handler);
    }
}

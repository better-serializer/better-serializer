<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain;

use BetterSerializer\DataBind\MetaData\Type\ExtensionTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Reader\Processor\ExtensionProcessor;
use BetterSerializer\DataBind\Reader\Processor\ProcessorInterface;

/**
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Processor\Converter\TypeChain
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
     * @return ProcessorInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function createProcessor(TypeInterface $type): ProcessorInterface
    {
        /* @var $type ExtensionTypeInterface */
        $customType = $this->customHandlerClasses[$type->getCustomType()];
        $handler = new $customType($type->getParameters());

        return new ExtensionProcessor($handler);
    }
}

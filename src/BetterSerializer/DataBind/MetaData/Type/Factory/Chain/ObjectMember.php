<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Reader\Property\Context\StringTypedPropertyContextInterface;
use BetterSerializer\DataBind\MetaData\Type\ObjectType;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;

/**
 * Class SimpleMember
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type\Factory\Chain
 */
final class ObjectMember extends ChainMember
{

    /**
     * @var string
     */
    private $className;

    /**
     * @param StringTypedPropertyContextInterface $context
     * @return bool
     */
    protected function isProcessable(StringTypedPropertyContextInterface $context): bool
    {
        $className = $this->getClassName($context);

        if ($className) {
            $this->className = $className;

            return true;
        }

        return false;
    }

    /**
     * @param StringTypedPropertyContextInterface $context
     * @return TypeInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function createType(StringTypedPropertyContextInterface $context): TypeInterface
    {
        return new ObjectType($this->className);
    }

    /**
     * @param StringTypedPropertyContextInterface $context
     * @return string|null
     */
    private function getClassName(StringTypedPropertyContextInterface $context): ?string
    {
        $stringType = $context->getStringType();

        if (class_exists($stringType)) {
            return $stringType;
        }

        $completeClass = $context->getNamespace() . '\\' . $stringType;
        $completeClass = str_replace('\\\\', '\\', $completeClass);

        return class_exists($completeClass) ? $completeClass : null;
    }
}

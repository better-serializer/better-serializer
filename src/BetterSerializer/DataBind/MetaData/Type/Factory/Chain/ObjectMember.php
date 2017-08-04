<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\StringFormTypeInterface;
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
     * @param StringFormTypeInterface $stringType
     * @return bool
     */
    protected function isProcessable(StringFormTypeInterface $stringType): bool
    {
        $className = $this->getClassName($stringType);

        if ($className) {
            $this->className = $className;

            return true;
        }

        return false;
    }

    /**
     * @param StringFormTypeInterface $stringType
     * @return TypeInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function createType(StringFormTypeInterface $stringType): TypeInterface
    {
        return new ObjectType($this->className);
    }

    /**
     * @param StringFormTypeInterface $stringType
     * @return string|null
     */
    private function getClassName(StringFormTypeInterface $stringType): ?string
    {
        $stringTypeString = $stringType->getStringType();

        if (class_exists($stringTypeString)) {
            return $stringTypeString;
        }

        $completeClass = $stringType->getNamespace() . '\\' . $stringTypeString;
        $completeClass = str_replace('\\\\', '\\', $completeClass);

        return class_exists($completeClass) ? $completeClass : null;
    }
}

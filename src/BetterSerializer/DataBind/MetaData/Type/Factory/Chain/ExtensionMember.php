<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\ExtensionObjectType;
use BetterSerializer\DataBind\MetaData\Type\ExtensionType;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\StringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use RuntimeException;

/**
 *
 */
final class ExtensionMember extends AbstractExtensionTypeMember
{

    /**
     * @var string
     */
    private $currentType;

    /**
     * @param StringFormTypeInterface $stringFormType
     * @return bool
     */
    protected function isProcessable(StringFormTypeInterface $stringFormType): bool
    {
        if (empty($this->customTypes)) {
            return false;
        }

        if (!preg_match("/^(?P<type>\\\?[A-Za-z][a-zA-Z0-9_\\\]*)/", $stringFormType->getStringType(), $matches)) {
            return false;
        }

        $this->currentType = $matches['type'];

        if (!isset($this->customTypes[$this->currentType])) {
            return false;
        }

        return true;
    }

    /**
     * @param StringFormTypeInterface $stringFormType
     * @return TypeInterface
     */
    protected function createType(StringFormTypeInterface $stringFormType): TypeInterface
    {
        $parameters = $this->parametersParser->parseParameters($stringFormType);

        if ($stringFormType->isClassOrInterface()) {
            return new ExtensionObjectType($this->currentType, $parameters);
        }

        return new ExtensionType($this->currentType, $parameters);
    }
}

<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\ExtensionCollectionType;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\Parameters\ParserInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\ContextStringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\StringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use RuntimeException;

/**
 *
 */
final class ExtensionCollectionMember extends AbstractExtensionTypeMember
{

    /**
     * @var TypeFactoryInterface
     */
    private $typeFactory;

    /**
     * @param TypeFactoryInterface $typeFactory
     * @param ParserInterface $parametersParser
     * @param array $customObjectClasses
     * @throws RuntimeException
     */
    public function __construct(
        TypeFactoryInterface $typeFactory,
        ParserInterface $parametersParser,
        array $customObjectClasses = []
    ) {
        $this->typeFactory = $typeFactory;
        parent::__construct($parametersParser, $customObjectClasses);
    }


    /**
     * @param StringFormTypeInterface $stringFormType
     * @return bool
     */
    protected function isProcessable(StringFormTypeInterface $stringFormType): bool
    {
        if (empty($this->customTypes)) {
            return false;
        }

        $currentType = $stringFormType->getStringType();

        if (!preg_match("/^(?P<type>\\\?[A-Za-z][a-zA-Z0-9_\\\]*)/", $currentType)) {
            return false;
        }

        if (!$stringFormType instanceof ContextStringFormTypeInterface || !$stringFormType->getCollectionValueType()) {
            return false;
        }

        if (!isset($this->customTypes[$currentType])) {
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

        /* @var $stringFormType ContextStringFormTypeInterface */
        $nestedStringFormType = $stringFormType->getCollectionValueType();
        $nestedType = $this->typeFactory->getType($nestedStringFormType);

        return new ExtensionCollectionType($stringFormType->getStringType(), $nestedType, $parameters);
    }
}

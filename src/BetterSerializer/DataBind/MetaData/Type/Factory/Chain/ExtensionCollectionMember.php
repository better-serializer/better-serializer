<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\ExtensionCollectionType;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\ContextStringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parameters\Parameters;
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
     * @param array $customObjectClasses
     * @throws RuntimeException
     */
    public function __construct(TypeFactoryInterface $typeFactory, array $customObjectClasses = [])
    {
        $this->typeFactory = $typeFactory;
        parent::__construct($customObjectClasses);
    }


    /**
     * @param ContextStringFormTypeInterface $stringFormType
     * @return bool
     */
    protected function isProcessable(ContextStringFormTypeInterface $stringFormType): bool
    {
        return $stringFormType->getCollectionValueType() && isset($this->customTypes[$stringFormType->getStringType()]);
    }

    /**
     * @param ContextStringFormTypeInterface $stringFormType
     * @return TypeInterface
     * @throws RuntimeException
     */
    protected function createType(ContextStringFormTypeInterface $stringFormType): TypeInterface
    {
        $parameters = $stringFormType->getParameters();

        if (!$parameters) {
            $parameters = new Parameters([]);
        }

        $nestedStringFormType = $stringFormType->getCollectionValueType();
        $nestedType = $this->typeFactory->getType($nestedStringFormType);

        return new ExtensionCollectionType($stringFormType->getStringType(), $nestedType, $parameters);
    }
}

<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type;

use BetterSerializer\DataBind\MetaData\Type\Parameters\ParametersInterface;

/**
 *
 */
final class ExtensionCollectionType extends AbstractExtensionObjectType implements ExtensionCollectionTypeInterface
{

    /**
     * @var TypeInterface
     */
    private $nestedType;

    /**
     * @param string $type
     * @param TypeInterface $nestedType
     * @param ParametersInterface $parameters
     * @throws \RuntimeException
     */
    public function __construct(string $type, TypeInterface $nestedType, ParametersInterface $parameters)
    {
        $this->nestedType = $nestedType;
        parent::__construct($type, $parameters);
    }

    /**
     * @return TypeInterface
     */
    public function getNestedType(): TypeInterface
    {
        return $this->nestedType;
    }

    /**
     * @param TypeInterface $type
     * @return bool
     */
    public function equals(TypeInterface $type): bool
    {
        if (!$type instanceof self || !parent::equals($type)) {
            return false;
        }

        return $this->nestedType->equals($type->nestedType);
    }

    /**
     * @param TypeInterface $type
     * @return bool
     */
    public function isCompatibleWith(TypeInterface $type): bool
    {
        if (parent::isCompatibleWith($type)) {
            if ($type instanceof self) {
                return $this->nestedType->isCompatibleWith($type->getNestedType());
            }

            return true;
        }

        return $type instanceof UnknownType;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return parent::__toString() . '::' . $this->getCustomType() . '<' . $this->nestedType . '>'
            . '(' . $this->parameters . ')';
    }

    /**
     * @return void
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function initType(): void
    {
        $this->type = TypeEnum::CUSTOM_COLLECTION();
    }
}

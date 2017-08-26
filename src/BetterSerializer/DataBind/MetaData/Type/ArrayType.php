<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Type;

/**
 * Class String
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type
 */
final class ArrayType extends AbstractCollectionType
{

    /**
     * StringDataType constructor.
     * @param TypeInterface $nestedType
     */
    public function __construct(TypeInterface $nestedType)
    {
        parent::__construct($nestedType);
    }

    /**
     * @return void
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function initType(): void
    {
        $this->type = TypeEnum::ARRAY();
    }

    /**
     * @param TypeInterface $type
     * @return bool
     */
    public function equals(TypeInterface $type): bool
    {
        /* @var $type ArrayType */
        return parent::equals($type) && $this->getNestedType()->equals($type->getNestedType());
    }

    /**
     * @param TypeInterface $type
     * @return bool
     */
    public function isCompatibleWith(TypeInterface $type): bool
    {
        /* @var $type ArrayType */
        $typeClass = get_class($type);

        return (
            (static::class === $typeClass && $this->getNestedType()->isCompatibleWith($type->getNestedType()))
            || $typeClass === UnknownType::class
        );
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return parent::__toString() . '<' . $this->getNestedType() . '>';
    }
}

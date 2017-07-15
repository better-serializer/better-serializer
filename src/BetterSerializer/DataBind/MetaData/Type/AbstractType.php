<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Type;

/**
 * Class AbstractDataType
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type
 */
abstract class AbstractType implements TypeInterface
{

    /**
     * @var TypeEnum
     */
    protected $type;

    /**
     * @return TypeEnum
     */
    public function getType(): TypeEnum
    {
        return $this->type;
    }

    /**
     * @param TypeInterface $type
     * @return bool
     */
    public function equals(TypeInterface $type): bool
    {
        if (static::class !== get_class($type)) {
            return false;
        }

        return $this->type->is($type->getType());
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->type->getValue();
    }
}

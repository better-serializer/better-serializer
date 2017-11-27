<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type;

use BetterSerializer\DataBind\MetaData\Type\Parameters\ParametersInterface;

/**
 * Class CustomObjectType
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type
 */
final class CustomType extends AbstractType implements CustomTypeInterface
{

    /**
     * @var string
     */
    private $customType;

    /**
     * @var ParametersInterface
     */
    private $parameters;

    /**
     * @param string $type
     * @param ParametersInterface $parameters
     */
    public function __construct(string $type, ParametersInterface $parameters)
    {
        $this->customType = $type;
        $this->parameters = $parameters;
        parent::__construct();
    }

    /**
     * @return string
     */
    public function getCustomType(): string
    {
        return $this->customType;
    }

    /**
     * @return ParametersInterface
     */
    public function getParameters(): ParametersInterface
    {
        return $this->parameters;
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

        return $this->customType === $type->customType && $this->parameters->equals($type->getParameters());
    }

    /**
     * @param TypeInterface $type
     * @return bool
     */
    public function isCompatibleWith(TypeInterface $type): bool
    {
        $typeClass = get_class($type);

        if ($typeClass === UnknownType::class) {
            return true;
        }

        return $this->equals($type);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return parent::__toString() . '<' . $this->customType . '>' . '(' . $this->parameters . ')';
    }

    /**
     * @return void
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function initType(): void
    {
        $this->type = TypeEnum::CUSTOM_TYPE();
    }
}

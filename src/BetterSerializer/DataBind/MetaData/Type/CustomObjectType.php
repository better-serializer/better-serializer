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
final class CustomObjectType extends AbstractObjectType implements CustomObjectTypeInterface
{

    /**
     * @var ParametersInterface
     */
    private $parameters;

    /**
     * @param string $className
     * @param ParametersInterface $parameters
     */
    public function __construct(string $className, ParametersInterface $parameters)
    {
        $this->parameters = $parameters;
        parent::__construct($className);
    }

    /**
     * @return string
     */
    public function getCustomType(): string
    {
        return $this->getClassName();
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
        if (!$type instanceof CustomObjectTypeInterface || !parent::equals($type)) {
            return false;
        }

        return $this->parameters->equals($type->getParameters());
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
        return parent::__toString() . '(' . $this->parameters . ')';
    }

    /**
     * @return void
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function initType(): void
    {
        $this->type = TypeEnum::CUSTOM_OBJECT();
    }
}

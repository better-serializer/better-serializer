<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parameters\ParametersInterface;
use RuntimeException;

/**
 *
 */
final class ExtensionType extends AbstractType implements ExtensionTypeInterface
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
     * @var TypeInterface
     */
    private $replacedType;

    /**
     * @param string $type
     * @param ParametersInterface $parameters
     * @param TypeInterface $replacedType
     * @throws RuntimeException
     */
    public function __construct(string $type, ParametersInterface $parameters, TypeInterface $replacedType = null)
    {
        if (class_exists($type) || interface_exists($type)) {
            throw new RuntimeException("This type shouldn't be used with classes or interfaces.");
        }

        $this->customType = $type;
        $this->parameters = $parameters;
        $this->replacedType = $replacedType;
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
     * @return bool
     */
    public function isInterface(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isClass(): bool
    {
        return false;
    }

    /**
     * @param TypeInterface $type
     * @return bool
     */
    public function isCompatibleWith(TypeInterface $type): bool
    {
        return $type instanceof UnknownType
            || ($this->replacedType !== null && $this->replacedType->isCompatibleWith($type))
            || $this->equals($type);
    }

    /**
     * @param InterfaceType $interface
     * @return bool
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function implementsInterface(InterfaceType $interface): bool
    {
        return false;
    }

    /**
     * @param AbstractObjectType $object
     * @return bool
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function extendsClass(AbstractObjectType $object): bool
    {
        return false;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return parent::__toString() . '::' . $this->customType . '(' . $this->parameters . ')';
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

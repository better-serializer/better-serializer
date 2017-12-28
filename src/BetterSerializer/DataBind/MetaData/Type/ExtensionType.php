<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type;

use BetterSerializer\DataBind\MetaData\Type\Parameters\ParametersInterface;
use RuntimeException;

/**
 *
 */
final class ExtensionType extends AbstractExtensionType
{

    /**
     * @param string $type
     * @param ParametersInterface $parameters
     * @throws RuntimeException
     */
    public function __construct(string $type, ParametersInterface $parameters)
    {
        if (class_exists($type) || interface_exists($type)) {
            throw new RuntimeException("This type shouldn't be used with classes or interfaces.");
        }

        parent::__construct($type, $parameters);
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
        // @todo store simple type with custom type to be able to exactly compare simple/primitive types
        return $type instanceof UnknownType
            //|| $type instanceof SimpleTypeInterface
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

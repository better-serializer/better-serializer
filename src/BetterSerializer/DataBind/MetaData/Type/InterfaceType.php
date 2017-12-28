<?php
declare(strict_types = 1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Type;

/**
 * Class String
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type
 */
final class InterfaceType extends AbstractType implements InterfaceTypeInterface
{

    /**
     * @var string
     */
    private $interfaceName;

    /**
     * StringDataType constructor.
     * @param string $interfaceName
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function __construct(string $interfaceName)
    {
        parent::__construct();
        $this->interfaceName = ltrim($interfaceName, '\\');
    }

    /**
     * @return void
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function initType(): void
    {
        $this->type = TypeEnum::INTERFACE();
    }

    /**
     * @return string
     */
    public function getInterfaceName(): string
    {
        return $this->interfaceName;
    }

    /**
     * @param TypeInterface $type
     * @return bool
     */
    public function equals(TypeInterface $type): bool
    {
        /* @var $type InterfaceType */
        return parent::equals($type) && $this->interfaceName === $type->getInterfaceName();
    }

    /**
     * @param TypeInterface $type
     * @return bool
     */
    public function isCompatibleWith(TypeInterface $type): bool
    {
        if ($type instanceof self) {
            return $this->equals($type) ||
                $this->implementsInterface($type) || $type->implementsInterface($this);
        } elseif ($type instanceof ObjectType) {
            return $type->implementsInterface($this);
        } elseif ($type instanceof ExtensionTypeInterface) {
            return $this->isCompatibleWithExtensionType($type);
        }

        return $type instanceof UnknownType;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return parent::__toString() . '<' . $this->interfaceName . '>';
    }

    /**
     * @param InterfaceType $interface
     * @return bool
     */
    public function implementsInterface(InterFaceType $interface): bool
    {
        return isset(class_implements($this->interfaceName)[$interface->interfaceName]);
    }

    /**
     * @param string $interfaceName
     * @return bool
     */
    public function implementsInterfaceAsString(string $interfaceName): bool
    {
        return isset(class_implements($this->interfaceName)[$interfaceName]);
    }

    /**
     * @param ExtensionTypeInterface $extension
     * @return bool
     */
    private function isCompatibleWithExtensionType(ExtensionTypeInterface $extension): bool
    {
        if ($extension->isClass()) {
            return $extension->implementsInterface($this);
        } elseif ($extension->isInterface()) {
            return $this->interfaceName === $extension->getCustomType() ||
                $extension->implementsInterface($this) ||
                $this->implementsInterfaceAsString($extension->getCustomType());
        }

        return false;
    }
}

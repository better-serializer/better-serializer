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
abstract class AbstractExtensionObjectType extends AbstractObjectType implements ExtensionObjectTypeInterface
{

    /**
     * @var ParametersInterface
     */
    protected $parameters;

    /**
     * @var bool
     */
    private $isInterface = false;

    /**
     * @var bool
     */
    private $isClass = false;

    /**
     * @param string $className
     * @param ParametersInterface $parameters
     * @throws RuntimeException
     */
    public function __construct(string $className, ParametersInterface $parameters)
    {
        $this->parameters = $parameters;

        if (class_exists($className)) {
            $this->isClass = true;
        } elseif (interface_exists($className)) {
            $this->isInterface = true;
        }

        if (!$this->isClass && !$this->isInterface) {
            throw new RuntimeException('This type must be used with classes or interfaces.');
        }

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
        if (!$type instanceof ExtensionObjectTypeInterface || !parent::equals($type)) {
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
        if ($this->isClass() && $type instanceof ObjectType) {
            return $this->isThisClassCompatibleWithClass($type);
        } elseif ($type instanceof InterfaceType) {
            return $this->isCompatibleWithInterface($type);
        } elseif ($this->isInterface() && $type instanceof AbstractObjectType) {
            return $this->isThisInterfaceCompatibleWithClass($type);
        } elseif ($type instanceof ExtensionTypeInterface) {
            return $this->isCompatibleWithExtensionType($type);
        }

        return $type instanceof UnknownType;
    }

    /**
     * @return bool
     */
    public function isInterface(): bool
    {
        return $this->isInterface;
    }

    /**
     * @return bool
     */
    public function isClass(): bool
    {
        return $this->isClass;
    }

    /**
     * @param InterfaceType $interface
     * @return bool
     */
    public function implementsInterface(InterfaceType $interface): bool
    {
        return isset(class_implements($this->getCustomType())[$interface->getInterfaceName()]);
    }

    /**
     * @param AbstractObjectType $class
     * @return bool
     */
    public function extendsClass(AbstractObjectType $class): bool
    {
        return $this->isClass() && isset(class_parents($this->getCustomType())[$class->getClassName()]);
    }

    /**
     * @return void
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function initType(): void
    {
        $this->type = TypeEnum::CUSTOM_OBJECT();
    }

    /**
     * @param ObjectType $type
     * @return bool
     */
    private function isThisClassCompatibleWithClass(ObjectType $type): bool
    {
        return $this->getCustomType() === $type->getClassName()
            || $this->extendsClass($type) || $type->extendsClass($this);
    }

    /**
     * @param InterfaceType $type
     * @return bool
     */
    private function isCompatibleWithInterface(InterfaceType $type): bool
    {
        return $this->getCustomType() === $type->getInterfaceName()
            || $this->implementsInterface($type);
    }

    /**
     * @param AbstractObjectType $type
     * @return bool
     */
    private function isThisInterfaceCompatibleWithClass(AbstractObjectType $type): bool
    {
        return $type->getClassName() === $this->getCustomType()
            || $type->implementsInterfaceAsString($this->getCustomType())
            || $type->extendsClassAsString($this->getCustomType());
    }

    /**
     * @param ExtensionTypeInterface $extension
     * @return bool
     */
    private function isCompatibleWithExtensionType(ExtensionTypeInterface $extension): bool
    {
        if ($this->isClass() && $extension->isClass()) {
            return $this->getCustomType() === $extension->getCustomType()
                || $this->extendsClassAsString($extension->getCustomType()) || $extension->extendsClass($this);
        } elseif ($extension->isInterface()) {
            return $this->getCustomType() === $extension->getCustomType()
                || $this->implementsInterfaceAsString($extension->getCustomType());
        }

        return false;
    }
}

<?php
declare(strict_types = 1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Type;

/**
 *
 */
abstract class AbstractClassType extends AbstractType implements ClassTypeInterface
{

    /**
     * @var string
     */
    protected $className;

    /**
     * @param string $className
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function __construct(string $className)
    {
        parent::__construct();
        $this->className = ltrim($className, '\\');
    }

    /**
     * @return void
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function initType(): void
    {
        $this->type = TypeEnum::CLASS_TYPE();
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @param TypeInterface $type
     * @return bool
     */
    public function equals(TypeInterface $type): bool
    {
        /* @var $type ClassType */
        return parent::equals($type) && $this->className === $type->getClassName();
    }

    /**
     * @param TypeInterface $type
     * @return bool
     */
    public function isCompatibleWith(TypeInterface $type): bool
    {
        if ($type instanceof static) {
            return $this->equals($type)
                || $this->extendsClass($type) || $type->extendsClass($this);
        } elseif ($type instanceof InterfaceType) {
            return $this->implementsInterface($type);
        } elseif ($type instanceof ExtensionTypeInterface) {
            return $this->isCompatibleWithExtensionType($type);
        }

        return $type instanceof UnknownType;
    }

    /**
     * @param InterfaceType $interface
     * @return bool
     */
    public function implementsInterface(InterfaceType $interface): bool
    {
        return isset(class_implements($this->getClassName())[$interface->getInterfaceName()]);
    }

    /**
     * @param string $interfaceName
     * @return bool
     */
    public function implementsInterfaceAsString(string $interfaceName): bool
    {
        return isset(class_implements($this->getClassName())[$interfaceName]);
    }

    /**
     * @param AbstractClassType $class
     * @return bool
     */
    public function extendsClass(AbstractClassType $class): bool
    {
        return isset(class_parents($this->getClassName())[$class->getClassName()]);
    }

    /**
     * @param string $className
     * @return bool
     */
    public function extendsClassAsString(string $className): bool
    {
        return isset(class_parents($this->getClassName())[$className]);
    }

    /**
     * @param ExtensionTypeInterface $extension
     * @return bool
     */
    private function isCompatibleWithExtensionType(ExtensionTypeInterface $extension): bool
    {
        if ($extension->isClass()) {
            return $this->className === $extension->getCustomType()
                || $this->extendsClassAsString($extension->getCustomType()) || $extension->extendsClass($this);
        } elseif ($extension->isInterface()) {
            return $this->implementsInterfaceAsString($extension->getCustomType());
        }

        return false;
    }
}

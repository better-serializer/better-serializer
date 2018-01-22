<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Extension\Registry;

use BetterSerializer\Extension\Registry\Registrator\RegistratorInterface;
use ReflectionClass;
use ReflectionException;
use RuntimeException;

/**
 *
 */
abstract class AbstractRegistry implements RegistryInterface
{

    /**
     * @var CollectionInterface
     */
    private $extensionsCollection;

    /**
     * @var RegistratorInterface[]
     */
    protected $registrators;

    /**
     * @param CollectionInterface $extensionsCollection
     * @param RegistratorInterface[] $registrators
     * @param string[] $extensionClasses
     * @throws ReflectionException
     * @throws RuntimeException
     */
    public function __construct(
        CollectionInterface $extensionsCollection,
        array $registrators,
        array $extensionClasses = []
    ) {
        $this->extensionsCollection = $extensionsCollection;
        $this->registrators = $registrators;
        $this->registerExtensions($extensionClasses);
    }

    /**
     * @param string $extensionClass
     * @throws ReflectionException
     * @throws RuntimeException
     */
    public function registerExtension(string $extensionClass): void
    {
        $reflClass = new ReflectionClass($extensionClass);

        foreach ($this->registrators as $registrator) {
            if ($registrator->register($reflClass)) {
                $this->extensionsCollection->registerExtension($extensionClass);

                return;
            }
        }

        throw new RuntimeException(
            sprintf(
                "Class '%s', doesn't implement any of these configured extension interfaces: %s",
                $reflClass->getName(),
                implode(
                    ', ',
                    array_map(function (RegistratorInterface $registrator) {
                        return $registrator->getExtTypeInterface();
                    }, $this->registrators)
                )
            )
        );
    }

    /**
     * @param string $typeString
     * @return bool
     */
    public function hasType(string $typeString): bool
    {
        return $this->extensionsCollection->hasType($typeString);
    }

    /**
     * @param string[] $extensionClasses
     * @throws ReflectionException
     * @throws RuntimeException
     */
    private function registerExtensions(array $extensionClasses): void
    {
        foreach ($extensionClasses as $extensionClass) {
            $this->registerExtension($extensionClass);
        }
    }
}

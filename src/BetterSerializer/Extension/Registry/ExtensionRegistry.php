<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Extension\Registry;

use BetterSerializer\Extension\Registry\Registrator\ExtensionRegistratorInterface;
use ReflectionClass;
use ReflectionException;
use RuntimeException;

/**
 *
 */
final class ExtensionRegistry implements ExtensionRegistryInterface
{

    /**
     * @var ExtensionsCollectionInterface
     */
    private $extensionsCollection;

    /**
     * @var ExtensionRegistratorInterface[]
     */
    private $registrators;

    /**
     * @param ExtensionsCollectionInterface $extensionsCollection
     * @param ExtensionRegistratorInterface[] $registrators
     */
    public function __construct(ExtensionsCollectionInterface $extensionsCollection, array $registrators)
    {
        $this->extensionsCollection = $extensionsCollection;
        $this->registrators = $registrators;
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
                    array_map(function (ExtensionRegistratorInterface $registrator) {
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
}

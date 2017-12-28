<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Extension\Registry;

use BetterSerializer\Extension\Registry\Registrator\ExtensionRegistratorInterface;
use ReflectionClass;
use RuntimeException;

/**
 *
 */
final class ExtensionRegistry implements ExtensionRegistryInterface
{

    /**
     * @var ExtensionRegistratorInterface[]
     */
    private $registrators;

    /**
     * @param ExtensionRegistratorInterface[] $registrators
     */
    public function __construct(array $registrators)
    {
        $this->registrators = $registrators;
    }

    /**
     * @param string $extensionClass
     * @throws RuntimeException
     */
    public function registerExtension(string $extensionClass): void
    {
        $reflClass = new ReflectionClass($extensionClass);

        foreach ($this->registrators as $registrator) {
            if ($registrator->register($reflClass)) {
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
}

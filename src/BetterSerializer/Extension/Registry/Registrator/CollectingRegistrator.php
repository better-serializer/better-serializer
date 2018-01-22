<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Extension\Registry\Registrator;

use BetterSerializer\Extension\Registry\Collection;
use BetterSerializer\Extension\Registry\CollectionInterface;
use ReflectionClass;

/**
 *
 */
final class CollectingRegistrator implements CollectingRegistratorInterface
{

    /**
     * @var string
     */
    private $extTypeInterface;

    /**
     * @var CollectionInterface
     */
    private $collection;

    /**
     * @param string $extTypeInterface
     * @param CollectionInterface|null $collection
     */
    public function __construct(string $extTypeInterface, CollectionInterface $collection = null)
    {
        if (!$collection) {
            $collection = new Collection();
        }

        $this->extTypeInterface = $extTypeInterface;
        $this->collection = $collection;
    }

    /**
     * @return string
     */
    public function getExtTypeInterface(): string
    {
        return $this->extTypeInterface;
    }

    /**
     * @return CollectionInterface
     */
    public function getCollection(): CollectionInterface
    {
        return $this->collection;
    }

    /**
     * @param ReflectionClass $reflClass
     * @return bool
     */
    public function register(ReflectionClass $reflClass): bool
    {
        if (!$this->isSupported($reflClass)) {
            return false;
        }

        $this->registerExtension($reflClass);

        return true;
    }

    /**
     * @param ReflectionClass $reflClass
     * @return bool
     */
    private function isSupported(ReflectionClass $reflClass): bool
    {
        return $reflClass->implementsInterface($this->extTypeInterface);
    }

    /**
     * @param ReflectionClass $reflClass
     */
    private function registerExtension(ReflectionClass $reflClass): void
    {
        $extensionClass = $reflClass->getName();
        $this->collection->registerExtension($extensionClass);
    }
}

<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Extension;

use BetterSerializer\Common\CollectionExtensionInterface;
use BetterSerializer\Common\CollectionAdapterInterface;
use BetterSerializer\DataBind\MetaData\Type\Parameters\ParametersInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Iterator;
use RuntimeException;
use Traversable;

/**
 *
 */
final class DoctrineCollection implements CollectionExtensionInterface
{

    /**
     * @var ParametersInterface
     */
    private $parameters;

    /**
     * @const string
     */
    private const TYPE = Collection::class;

    /**
     * @param ParametersInterface $parameters
     */
    public function __construct(ParametersInterface $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @return string
     */
    public static function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @param mixed $collection
     * @return bool
     * @throws RuntimeException
     */
    public function isEmpty($collection): bool
    {
        if (!$collection instanceof Collection) {
            throw new RuntimeException(sprintf('%s is not a Doctrine collection.', get_class($collection)));
        }

        return $collection->isEmpty();
    }

    /**
     * @param Object $collection
     * @return CollectionAdapterInterface
     * @throws RuntimeException
     */
    public function getAdapter($collection): CollectionAdapterInterface
    {
        if (!$collection instanceof Collection) {
            throw new RuntimeException(sprintf('%s is not a Doctrine collection.', get_class($collection)));
        }

        return $this->createAdapter($collection);
    }

    /**
     * @return CollectionAdapterInterface
     */
    public function getNewAdapter(): CollectionAdapterInterface
    {
        $collection = new ArrayCollection();

        return $this->createAdapter($collection);
    }

    /**
     * @param Collection $collection
     * @return CollectionAdapterInterface
     */
    private function createAdapter(Collection $collection): CollectionAdapterInterface
    {
        return new class($collection) implements CollectionAdapterInterface {
            /**
             * @var Collection
             */
            private $collection;

            /**
             * @param Collection $collection
             */
            public function __construct(Collection $collection)
            {
                $this->collection = $collection;
            }

            /**
             * @return Iterator|Traversable
             */
            public function getIterator(): Iterator
            {
                return $this->collection->getIterator();
            }

            /**
             * @param int|string $offset
             * @return bool
             */
            public function offsetExists($offset): bool
            {
                return $this->collection->containsKey($offset);
            }

            /**
             * @param int|string $offset
             * @return mixed
             */
            public function offsetGet($offset)
            {
                return $this->collection->get($offset);
            }

            /**
             * @param int|string $offset
             * @param mixed $value
             */
            public function offsetSet($offset, $value): void
            {
                $this->collection->set($offset, $value);
            }

            /**
             * @param int|string $offset
             */
            public function offsetUnset($offset): void
            {
                $this->collection->remove($offset);
            }

            /**
             * @return Collection|mixed
             */
            public function getCollection()
            {
                return $this->collection;
            }
        };
    }
}

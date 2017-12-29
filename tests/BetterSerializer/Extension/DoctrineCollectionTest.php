<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Extension;

use BetterSerializer\Common\CollectionAdapterInterface;
use BetterSerializer\DataBind\MetaData\Type\Parameters\ParametersInterface;
use BetterSerializer\Dto\CarInterface;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;
use Iterator;
use RuntimeException;

/**
 *
 */
class DoctrineCollectionTest extends TestCase
{

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testEverything(): void
    {
        $isEmpty = true;
        $collection = $this->createMock(Collection::class);
        $collection->expects(self::once())
            ->method('isEmpty')
            ->willReturn($isEmpty);

        $parameters = $this->createMock(ParametersInterface::class);

        $extension = new DoctrineCollection($parameters);

        self::assertSame(Collection::class, DoctrineCollection::getType());
        self::assertNull(DoctrineCollection::getReplacedType());
        self::assertSame($isEmpty, $extension->isEmpty($collection));
        self::assertInstanceOf(CollectionAdapterInterface::class, $extension->getAdapter($collection));
        self::assertInstanceOf(CollectionAdapterInterface::class, $extension->getNewAdapter());
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /[A-Z][a-z0-9_\\]+ is not a Doctrine collection\./
     */
    public function testIsEmptyThrowsOnUnsupportedType(): void
    {
        $collection = $this->createMock(CarInterface::class);
        $parameters = $this->createMock(ParametersInterface::class);

        $extension = new DoctrineCollection($parameters);
        $extension->isEmpty($collection);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /[A-Z][a-z0-9_\\]+ is not a Doctrine collection\./
     */
    public function testGetAdapterThrowsOnUnsupportedType(): void
    {
        $collection = $this->createMock(CarInterface::class);
        $parameters = $this->createMock(ParametersInterface::class);

        $extension = new DoctrineCollection($parameters);
        $extension->getAdapter($collection);
    }

    /**
     *
     */
    public function testCollectionAdapter(): void
    {
        $offset = 1;
        $value = 'test';

        $iterator = $this->createMock(Iterator::class);

        $collection = $this->createMock(Collection::class);
        $collection->expects(self::once())
            ->method('getIterator')
            ->willReturn($iterator);
        $collection->expects(self::once())
            ->method('containsKey')
            ->with($offset)
            ->willReturn(false);
        $collection->expects(self::once())
            ->method('get')
            ->with($offset);
        $collection->expects(self::once())
            ->method('set')
            ->with($offset, $value);
        $collection->expects(self::once())
            ->method('remove')
            ->with($offset);

        $parameters = $this->createMock(ParametersInterface::class);
        $extension = new DoctrineCollection($parameters);
        $adapter = $extension->getAdapter($collection);

        self::assertInstanceOf(Iterator::class, $adapter->getIterator());
        self::assertSame($collection, $adapter->getCollection());
        $adapter->offsetExists($offset);
        $adapter->offsetGet($offset);
        $adapter->offsetSet($offset, $value);
        $adapter->offsetUnset($offset);
    }
}

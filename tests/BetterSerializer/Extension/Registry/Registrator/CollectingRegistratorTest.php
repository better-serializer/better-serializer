<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Extension\Registry\Registrator;

use BetterSerializer\Common\CollectionExtensionInterface;
use BetterSerializer\Common\TypeExtensionInterface;
use BetterSerializer\Extension\Registry\CollectionInterface;
use BetterSerializer\Helper\ExtensionMockFactory;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 *
 */
class CollectingRegistratorTest extends TestCase
{

    /**
     * @dataProvider getIsSupportedDataProvider
     * @param string $extTypeInterface
     * @param string $className
     * @param bool $expectedResult
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \ReflectionException
     */
    public function testCreate(string $extTypeInterface, string $className, bool $expectedResult): void
    {
        $expectation = $expectedResult ? 1 : 0;

        $collection = $this->createMock(CollectionInterface::class);
        $collection->expects(self::exactly($expectation))
            ->method('registerExtension')
            ->with($className);

        $reflClass = new ReflectionClass($className);

        $registrator = new CollectingRegistrator($extTypeInterface, $collection);

        self::assertSame($expectedResult, $registrator->register($reflClass));
        self::assertSame($extTypeInterface, $registrator->getExtTypeInterface());
        self::assertSame($collection, $registrator->getCollection());
    }

    /**
     * @dataProvider getIsSupportedDataProvider
     * @param string $extTypeInterface
     * @param string $className
     * @param bool $expectedResult
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \ReflectionException
     */
    public function testCreateWithoutCollection(string $extTypeInterface, string $className, bool $expectedResult): void
    {
        $reflClass = new ReflectionClass($className);

        $registrator = new CollectingRegistrator($extTypeInterface);

        self::assertSame($expectedResult, $registrator->register($reflClass));
        self::assertSame($extTypeInterface, $registrator->getExtTypeInterface());
    }

    /**
     * @return array
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \ReflectionException
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function getIsSupportedDataProvider(): array
    {
        $class1 = get_class(ExtensionMockFactory::createTypeExcensionMock('CustomType'));
        $class2 = get_class(ExtensionMockFactory::createCollectionExtensionMock('CustomType', true));

        return [
            [TypeExtensionInterface::class, $class1, true],
            [CollectionExtensionInterface::class, $class2, true],
            [TypeExtensionInterface::class, $class2, false],
            [CollectionExtensionInterface::class, $class1, false],
        ];
    }
}

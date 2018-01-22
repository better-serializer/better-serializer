<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Extension\Registry;

use BetterSerializer\Helper\ExtensionMockFactory;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class CollectionTest extends TestCase
{

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testEverything(): void
    {
        $typeString = 'test';
        $extension = ExtensionMockFactory::createTypeExcensionMock($typeString);
        $extensionClass = get_class($extension);

        $collection = new Collection();
        $collection->registerExtension($extensionClass);

        self::assertTrue($collection->hasType($typeString));
        self::assertFalse($collection->hasType($typeString . 'xx'));

        $array = $collection->toArray();

        self::assertCount(1, $array);
        self::assertArrayHasKey($typeString, $array);
        self::assertSame($extensionClass, $array[$typeString]);
    }
}

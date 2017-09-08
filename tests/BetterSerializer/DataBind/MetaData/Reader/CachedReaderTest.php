<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\DataBind\MetaData\Model\MetaDataInterface;
use BetterSerializer\Dto\Car;
use Doctrine\Common\Cache\Cache;
use PHPUnit\Framework\TestCase;

/**
 * Class CachedReaderTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 */
class CachedReaderTest extends TestCase
{

    /**
     *
     */
    public function testRead(): void
    {
        $className = Car::class;
        $cacheKey = 'metaData||' . $className;
        $metaData = $this->createMock(MetaDataInterface::class);
        $nestedReader = $this->createMock(ReaderInterface::class);

        $nestedReader->expects(self::once())
            ->method('read')
            ->with($className)
            ->willReturn($metaData);

        $cache = $this->createMock(Cache::class);
        $cache->expects(self::exactly(2))
            ->method('contains')
            ->with($cacheKey)
            ->willReturnOnConsecutiveCalls(false, true);
        $cache->expects(self::once())
            ->method('save')
            ->with($cacheKey, $metaData);
        $cache->expects(self::once())
            ->method('fetch')
            ->with($cacheKey)
            ->willReturn($metaData);

        $reader = new CachedReader($nestedReader, $cache);
        $readMetaData = $reader->read($className);

        self::assertSame($metaData, $readMetaData);

        $readMetaData2 = $reader->read($className);

        self::assertSame($metaData, $readMetaData2);
    }
}

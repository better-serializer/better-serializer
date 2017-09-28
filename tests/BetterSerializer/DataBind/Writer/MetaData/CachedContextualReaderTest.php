<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\MetaData;

use BetterSerializer\DataBind\MetaData\Model\MetaDataInterface;
use BetterSerializer\DataBind\Writer\SerializationContextInterface;
use BetterSerializer\Dto\Car;
use Doctrine\Common\Cache\Cache;
use PHPUnit\Framework\TestCase;

/**
 * Class CachedContextualReaderTest
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\MetaData
 */
class CachedContextualReaderTest extends TestCase
{

    /**
     *
     */
    public function testRead(): void
    {
        $className = Car::class;
        $context = $this->createMock(SerializationContextInterface::class);

        $metaData = $this->createMock(MetaDataInterface::class);

        $reader = $this->createMock(ContextualReaderInterface::class);
        $reader->expects(self::once())
            ->method('read')
            ->with($className, $context)
            ->willReturn($metaData);

        $cache = $this->createMock(Cache::class);
        $cache->expects(self::exactly(2))
            ->method('contains')
            ->willReturnOnConsecutiveCalls(false, true);
        $cache->expects(self::once())
            ->method('save')
            ->with(self::anything(), $metaData);
        $cache->expects(self::once())
            ->method('fetch')
            ->willReturn($metaData);

        $cachedReader = new CachedContextualReader($reader, $cache);
        $extractedMetaData = $cachedReader->read($className, $context);

        self::assertSame($metaData, $extractedMetaData);

        $extractedMetaData2 = $cachedReader->read($className, $context);

        self::assertSame($metaData, $extractedMetaData2);
    }
}

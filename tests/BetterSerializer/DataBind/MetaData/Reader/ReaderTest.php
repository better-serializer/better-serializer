<?php
/**
 * @author  mfris
 */
declare(strict_types = 1);

namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\DataBind\MetaData\ClassMetadataInterface;
use BetterSerializer\DataBind\MetaData\MetaData;
use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;
use Mockery;

/**
 * Class ReaderTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class ReaderTest extends TestCase
{

    /**
     *
     */
    public function testRead(): void
    {
        $classMetadata = Mockery::mock(ClassMetadataInterface::class);

        /* @var $classReader ClassReader */
        $classReader = Mockery::mock(ClassReaderInterface::class, ['getClassMetadata' => $classMetadata]);

        /* @var $propertyReader PropertyReader */
        $propertyReader = Mockery::mock(PropertyReaderInterface::class, ['getPropertyMetadata' => []]);

        $reader = new Reader($classReader, $propertyReader);
        $metaData = $reader->read(Car::class);

        self::assertInstanceOf(MetaData::class, $metaData);
    }
}

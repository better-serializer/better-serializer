<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\DataBind\MetaData\ClassMetaDataInterface;
use BetterSerializer\DataBind\MetaData\MetaData;
use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;
use Mockery;
use LogicException;
use ReflectionClass;

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
        $classMetadata = Mockery::mock(ClassMetaDataInterface::class);

        /* @var $classReader ClassReader */
        $classReader = Mockery::mock(ClassReaderInterface::class, ['getClassMetadata' => $classMetadata]);

        /* @var $propertyReader PropertyReader */
        $propertyReader = Mockery::mock(PropertyReaderInterface::class, ['getPropertyMetadata' => []]);

        $reader = new Reader($classReader, $propertyReader);
        $metaData = $reader->read(Car::class);

        self::assertInstanceOf(MetaData::class, $metaData);
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessageRegExp /Class "[a-zA-Z0-9_]+" is not user-defined/
     */
    public function testReadSystemClassThrowsException(): void
    {
        $classMetadata = Mockery::mock(ClassMetaDataInterface::class);

        /* @var $classReader ClassReader */
        $classReader = Mockery::mock(ClassReaderInterface::class, ['getClassMetadata' => $classMetadata]);

        /* @var $propertyReader PropertyReader */
        $propertyReader = Mockery::mock(PropertyReaderInterface::class, ['getPropertyMetadata' => []]);

        $reader = new Reader($classReader, $propertyReader);
        $reader->read(ReflectionClass::class);
    }
}

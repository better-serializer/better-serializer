<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\DataBind\MetaData\Model\ClassModel\ClassMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\MetaData;
use BetterSerializer\DataBind\MetaData\Reader\Property\PropertyReader;
use BetterSerializer\DataBind\MetaData\Reader\Property\PropertyReaderInterface;
use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;
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
        $classMetadata = $this->getMockBuilder(ClassMetaDataInterface::class)->getMock();

        $classReader = $this->getMockBuilder(ClassReaderInterface::class)->getMock();
        $classReader->expects(self::once())
            ->method('getClassMetadata')
            ->willReturn($classMetadata);
        /* @var $classReader ClassReader */

        $propertyReader = $this->getMockBuilder(PropertyReaderInterface::class)->getMock();
        $propertyReader->expects(self::once())
            ->method('getPropertyMetadata')
            ->willReturn([]);
        /* @var $propertyReader PropertyReader */

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
        $classReader = $this->getMockBuilder(ClassReaderInterface::class)->getMock();
        /* @var $classReader ClassReader */

        $propertyReader = $this->getMockBuilder(PropertyReaderInterface::class)->getMock();
        /* @var $propertyReader PropertyReader */

        $reader = new Reader($classReader, $propertyReader);
        $reader->read(ReflectionClass::class);
    }
}

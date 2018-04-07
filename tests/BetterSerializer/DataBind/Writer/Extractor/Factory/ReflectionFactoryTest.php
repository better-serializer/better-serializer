<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Extractor\Factory;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\ReflectionPropertyMetaDataInterface;
use BetterSerializer\DataBind\Writer\Extractor\Property\PropertyExtractor;
use BetterSerializer\Reflection\ReflectionPropertyInterface;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 *
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class ReflectionFactoryTest extends TestCase
{

    /**
     *
     */
    public function testNewExtractor(): void
    {
        $reflPropertyStub = $this->createMock(ReflectionPropertyInterface::class);
        $propertyMetadataStub = $this->createMock(ReflectionPropertyMetaDataInterface::class);
        $propertyMetadataStub->expects(self::once())
            ->method('getReflectionProperty')
            ->willReturn($reflPropertyStub);

        $factory = new PropertyFactory();
        $extractor = $factory->newExtractor($propertyMetadataStub);

        self::assertInstanceOf(PropertyExtractor::class, $extractor);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Not a ReflectionPropertyMetaDataInterface - [a-zA-Z0-9_]+/
     */
    public function testNewExtractorThrowsException(): void
    {
        $propertyMetadataStub = $this->createMock(PropertyMetaDataInterface::class);

        $factory = new PropertyFactory();
        $factory->newExtractor($propertyMetadataStub);
    }
}

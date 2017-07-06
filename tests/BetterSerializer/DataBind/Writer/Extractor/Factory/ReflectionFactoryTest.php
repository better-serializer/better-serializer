<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Extractor\Factory;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\ReflectionPropertyMetaDataInterface;
use BetterSerializer\DataBind\Writer\Extractor\Property\ReflectionExtractor;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use RuntimeException;

/**
 * Class ReflectionFactoryTest
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Extractor\Factory
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class ReflectionFactoryTest extends TestCase
{

    /**
     *
     */
    public function testNewExtractor(): void
    {
        $reflPropertyStub = $this->getMockBuilder(ReflectionProperty::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $reflPropertyStub ReflectionProperty */

        $propertyMetadataStub = $this->getMockBuilder(ReflectionPropertyMetaDataInterface::class)->getMock();
        $propertyMetadataStub->expects(self::once())
            ->method('getReflectionProperty')
            ->willReturn($reflPropertyStub);
        /* @var $propertyMetadataStub PropertyMetaDataInterface */

        $factory = new ReflectionFactory();
        $extractor = $factory->newExtractor($propertyMetadataStub);

        self::assertInstanceOf(ReflectionExtractor::class, $extractor);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Not a ReflectionPropertyMetaDataInterface - [a-zA-Z0-9_]+/
     */
    public function testNewExtractorThrowsException(): void
    {
        /* @var $propertyMetadataStub PropertyMetaDataInterface */
        $propertyMetadataStub = $this->getMockBuilder(PropertyMetaDataInterface::class)->getMock();

        $factory = new ReflectionFactory();
        $factory->newExtractor($propertyMetadataStub);
    }
}

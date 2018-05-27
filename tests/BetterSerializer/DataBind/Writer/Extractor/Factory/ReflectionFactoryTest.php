<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Extractor\Factory;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\Writer\Extractor\Property\ReflectionExtractor;
use BetterSerializer\Reflection\ReflectionPropertyInterface;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class ReflectionFactoryTest extends TestCase
{

    /**
     *
     */
    public function testNewExtractor(): void
    {
        $reflPropertyStub = $this->createMock(ReflectionPropertyInterface::class);
        $propertyMetadataStub = $this->createMock(PropertyMetaDataInterface::class);
        $propertyMetadataStub->expects(self::once())
            ->method('getReflectionProperty')
            ->willReturn($reflPropertyStub);

        $factory = new ReflectionFactory();
        $extractor = $factory->newExtractor($propertyMetadataStub);

        self::assertInstanceOf(ReflectionExtractor::class, $extractor);
    }
}

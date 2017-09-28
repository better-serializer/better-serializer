<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * Class ShrinkingPropertiesMetaDataTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner
 */
class ShrinkingPropertiesMetaDataTest extends TestCase
{

    /**
     *
     */
    public function testShrinkBy(): void
    {
        $propertyName = 'test';
        $propertyMetaData = $this->getMockBuilder(PropertyMetaDataInterface::class)->getMock();

        /* @var $propertyMetaData PropertyMetaDataInterface */
        $propertiesMetaData = new ShrinkingPropertiesMetaData([$propertyName => $propertyMetaData]);

        self::assertSame($propertyMetaData, $propertiesMetaData->shrinkBy($propertyName));
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /MetaData missing for '[a-zA-Z_]+'./
     */
    public function testShrinkByThrowsExceptionOnConsecutiveRun(): void
    {
        $propertyName = 'test';
        $propertyMetaData = $this->getMockBuilder(PropertyMetaDataInterface::class)->getMock();

        /* @var $propertyMetaData PropertyMetaDataInterface */
        $propertiesMetaData = new ShrinkingPropertiesMetaData([$propertyName => $propertyMetaData]);

        self::assertSame($propertyMetaData, $propertiesMetaData->shrinkBy($propertyName));

        // throw exception now
        $propertiesMetaData->shrinkBy($propertyName);
    }

    /**
     *
     */
    public function testHasProperty(): void
    {
        $propertyName = 'test';
        $propertyMetaData = $this->getMockBuilder(PropertyMetaDataInterface::class)->getMock();

        /* @var $propertyMetaData PropertyMetaDataInterface */
        $propertiesMetaData = new ShrinkingPropertiesMetaData([$propertyName => $propertyMetaData]);

        self::assertTrue($propertiesMetaData->hasProperty($propertyName));

        $propertiesMetaData->shrinkBy($propertyName);

        self::assertFalse($propertiesMetaData->hasProperty($propertyName));
    }
}

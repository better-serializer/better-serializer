<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Model;

use BetterSerializer\DataBind\MetaData\Model\ClassModel\ClassMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Factory\InstantiatorFactoryInterface;
use PHPUnit\Framework\TestCase;

/**
 * ClassModel MetaDataTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class MetaDataTest extends TestCase
{

    /**
     *
     */
    public function testInstantiation(): void
    {
        $classMetadata = $this->getMockBuilder(ClassMetaDataInterface::class)->getMock();
        $propertiesMetaData = [
            $this->getMockBuilder(PropertyMetaDataInterface::class)->getMock(),
            $this->getMockBuilder(PropertyMetaDataInterface::class)->getMock(),
        ];
        $constrParamsMetaData = [
            $this->getMockBuilder(InstantiatorFactoryInterface::class)->getMock(),
            $this->getMockBuilder(InstantiatorFactoryInterface::class)->getMock(),
        ];

        /* @var $classMetadata ClassMetaDataInterface */
        $metaData = new MetaData($classMetadata, $propertiesMetaData, $constrParamsMetaData);

        self::assertInstanceOf(ClassMetaDataInterface::class, $metaData->getClassMetadata());
        self::assertSame($classMetadata, $metaData->getClassMetadata());
        self::assertInternalType('array', $metaData->getPropertiesMetadata());
        self::assertCount(2, $metaData->getPropertiesMetadata());
        self::assertInternalType('array', $metaData->getConstructorParamsMetaData());
        self::assertCount(2, $metaData->getConstructorParamsMetaData());

        foreach ($metaData->getPropertiesMetadata() as $propertyMetaData) {
            self::assertInstanceOf(PropertyMetaDataInterface::class, $propertyMetaData);
        }
    }

    /**
     *
     */
    public function testInstantiationWithoutConstructorParams(): void
    {
        $classMetadata = $this->getMockBuilder(ClassMetaDataInterface::class)->getMock();
        $propertiesMetaData = [
            $this->getMockBuilder(PropertyMetaDataInterface::class)->getMock(),
            $this->getMockBuilder(PropertyMetaDataInterface::class)->getMock(),
        ];

        /* @var $classMetadata ClassMetaDataInterface */
        $metaData = new MetaData($classMetadata, $propertiesMetaData);

        self::assertInstanceOf(ClassMetaDataInterface::class, $metaData->getClassMetadata());
        self::assertSame($classMetadata, $metaData->getClassMetadata());
        self::assertInternalType('array', $metaData->getPropertiesMetadata());
        self::assertCount(2, $metaData->getPropertiesMetadata());
        self::assertInternalType('array', $metaData->getConstructorParamsMetaData());
        self::assertCount(0, $metaData->getConstructorParamsMetaData());

        foreach ($metaData->getPropertiesMetadata() as $propertyMetaData) {
            self::assertInstanceOf(PropertyMetaDataInterface::class, $propertyMetaData);
        }
    }
}

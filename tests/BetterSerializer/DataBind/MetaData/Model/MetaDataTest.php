<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Model;

use BetterSerializer\DataBind\MetaData\Model\ClassModel\ClassMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
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

        /* @var $classMetadata ClassMetaDataInterface */
        $metaData = new MetaData($classMetadata, $propertiesMetaData);

        self::assertInstanceOf(ClassMetaDataInterface::class, $metaData->getClassMetadata());
        self::assertSame($classMetadata, $metaData->getClassMetadata());
        self::assertInternalType('array', $metaData->getPropertiesMetadata());
        self::assertCount(2, $metaData->getPropertiesMetadata());

        foreach ($metaData->getPropertiesMetadata() as $propertyMetaData) {
            self::assertInstanceOf(PropertyMetaDataInterface::class, $propertyMetaData);
        }
    }
}

<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData;

use PHPUnit\Framework\TestCase;
use Mockery;

/**
 * Class MetaDataTest
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
        $classMetadata = Mockery::mock(ClassMetadataInterface::class);
        $propertiesMetaData = [
            Mockery::mock(PropertyMetadataInterface::class),
            Mockery::mock(PropertyMetadataInterface::class),
        ];

        /* @var $classMetadata ClassMetadataInterface */
        $metaData = new MetaData($classMetadata, $propertiesMetaData);

        self::assertInstanceOf(ClassMetadataInterface::class, $metaData->getClassMetadata());
        self::assertSame($classMetadata, $metaData->getClassMetadata());
        self::assertInternalType('array', $metaData->getPropertiesMetadata());
        self::assertCount(2, $metaData->getPropertiesMetadata());

        foreach ($metaData->getPropertiesMetadata() as $propertyMetaData) {
            self::assertInstanceOf(PropertyMetadataInterface::class, $propertyMetaData);
        }
    }
}

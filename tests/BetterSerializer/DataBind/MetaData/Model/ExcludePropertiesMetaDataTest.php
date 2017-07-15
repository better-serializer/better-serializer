<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Model;

use BetterSerializer\DataBind\MetaData\Model\ClassModel\ClassMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyTuple\PropertyWithConstructorParamTupleInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Factory\ChainedInstantiatorFactoryInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class ExcludePropertiesMetaDataTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Model
 */
class ExcludePropertiesMetaDataTest extends TestCase
{

    /**
     *
     */
    public function testInstantiation(): void
    {
        $classMetadata = $this->getMockBuilder(ClassMetaDataInterface::class)->getMock();

        $key1 = 'test1';
        $key2 = 'test2';
        $propertiesMetaData = [
            $key1 => $this->getMockBuilder(PropertyMetaDataInterface::class)->getMock(),
            $key2 => $this->getMockBuilder(PropertyMetaDataInterface::class)->getMock(),
        ];
        $constrParamsMetaData = [
            $this->getMockBuilder(ChainedInstantiatorFactoryInterface::class)->getMock(),
            $this->getMockBuilder(ChainedInstantiatorFactoryInterface::class)->getMock(),
        ];

        $constrParamTuples = [
            $this->getMockBuilder(PropertyWithConstructorParamTupleInterface::class)->getMock(),
            $this->getMockBuilder(PropertyWithConstructorParamTupleInterface::class)->getMock(),
        ];

        $isInstatiable = true;

        $metaData = $this->getMockBuilder(MetaDataInterface::class)->getMock();
        $metaData->expects(self::exactly(2))
            ->method('getClassMetadata')
            ->willReturn($classMetadata);
        $metaData->expects(self::exactly(2))
            ->method('getConstructorParamsMetaData')
            ->willReturn($constrParamsMetaData);
        $metaData->expects(self::exactly(2))
            ->method('getPropertyWithConstructorParamTuples')
            ->willReturn($constrParamTuples);
        $metaData->expects(self::once())
            ->method('isInstantiableByConstructor')
            ->willReturn($isInstatiable);
        $metaData->expects(self::exactly(1))
            ->method('getPropertiesMetadata')
            ->willReturn($propertiesMetaData);

        /* @var $metaData MetaDataInterface */
        $excludedMetaData = new ExcludePropertiesMetaData($metaData, [$key1]);

        self::assertInstanceOf(ClassMetaDataInterface::class, $excludedMetaData->getClassMetadata());
        self::assertSame($classMetadata, $excludedMetaData->getClassMetadata());
        self::assertInternalType('array', $excludedMetaData->getConstructorParamsMetaData());
        self::assertCount(2, $excludedMetaData->getConstructorParamsMetaData());
        self::assertInternalType('array', $excludedMetaData->getPropertyWithConstructorParamTuples());
        self::assertCount(2, $excludedMetaData->getPropertyWithConstructorParamTuples());
        self::assertSame($isInstatiable, $excludedMetaData->isInstantiableByConstructor());
        self::assertInternalType('array', $excludedMetaData->getPropertiesMetadata());
        self::assertCount(1, $excludedMetaData->getPropertiesMetadata());
        self::assertArrayHasKey($key2, $excludedMetaData->getPropertiesMetadata());

        foreach ($excludedMetaData->getPropertiesMetadata() as $propertyMetaData) {
            self::assertInstanceOf(PropertyMetaDataInterface::class, $propertyMetaData);
        }
    }
}

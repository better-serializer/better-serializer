<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\MetaData;

use BetterSerializer\DataBind\MetaData\Model\ClassModel\ClassMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\MetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\Writer\SerializationContextInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class ContextualMetaDataTest
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\MetaData
 */
class ContextualMetaDataTest extends TestCase
{

    /**
     *
     */
    public function testEverything(): void
    {
        $group1 = 'default';
        $group2 = 'g2';
        $group3 = 'g3';

        $property1 = $this->createMock(PropertyMetaDataInterface::class);
        $property1->method('getGroups')
            ->willReturn([$group1, $group2]);

        $property2 = $this->createMock(PropertyMetaDataInterface::class);
        $property2->method('getGroups')
            ->willReturn([$group2, $group3]);

        $property3 = $this->createMock(PropertyMetaDataInterface::class);
        $property3->method('getGroups')
            ->willReturn([$group3]);

        $classMetaData = $this->createMock(ClassMetaDataInterface::class);
        $cpmd = [];
        $pwcpt = [];

        $decorated = $this->createMock(MetaDataInterface::class);
        $decorated->expects(self::once())
            ->method('getPropertiesMetaData')
            ->willReturn(
                [
                    'property1' => $property1,
                    'property2' => $property2,
                    'property3' => $property3
                ]
            );
        $decorated->expects(self::once())
            ->method('getClassMetaData')
            ->willReturn($classMetaData);
        $decorated->expects(self::once())
            ->method('getConstructorParamsMetaData')
            ->willReturn($cpmd);
        $decorated->expects(self::once())
            ->method('getPropertyWithConstructorParamTuples')
            ->willReturn($pwcpt);
        $decorated->expects(self::once())
            ->method('isInstantiableByConstructor')
            ->willReturn(true);

        $context = $this->createMock(SerializationContextInterface::class);
        $context->expects(self::once())
            ->method('getGroups')
            ->willReturn([$group1, $group2]);

        $metaData = new ContextualMetaData($decorated, $context);
        $properties = $metaData->getPropertiesMetadata();

        self::assertCount(2, $properties);
        self::assertSame($property1, $properties['property1']);
        self::assertSame($property2, $properties['property2']);
        self::assertSame($properties, $metaData->getPropertiesMetadata());

        self::assertSame($classMetaData, $metaData->getClassMetadata());
        self::assertSame($cpmd, $metaData->getConstructorParamsMetaData());
        self::assertSame($pwcpt, $metaData->getPropertyWithConstructorParamTuples());
        self::assertTrue($metaData->isInstantiableByConstructor());
    }
}

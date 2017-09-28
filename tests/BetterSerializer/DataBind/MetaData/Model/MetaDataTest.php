<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Model;

use BetterSerializer\DataBind\MetaData\Model\ClassModel\ClassMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\ConstructorParamModel\ConstructorParamMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyTuple\PropertyWithConstructorParamTuple;
use BetterSerializer\DataBind\MetaData\Model\PropertyTuple\PropertyWithConstructorParamTupleInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Factory\ChainedInstantiatorFactoryInterface;
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
            $this->getMockBuilder(ChainedInstantiatorFactoryInterface::class)->getMock(),
            $this->getMockBuilder(ChainedInstantiatorFactoryInterface::class)->getMock(),
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

    /**
     * @dataProvider mixedParamsAndConstructorParamsProvider
     * @param ClassMetaDataInterface $classMetaData
     * @param array $propertiesMetaData
     * @param array $constructorParams
     * @param bool $expectedResult
     * @param PropertyWithConstructorParamTuple[] $expectedTupleKeys
     */
    public function testIsInstantiableByConstructor(
        ClassMetaDataInterface $classMetaData,
        array $propertiesMetaData,
        array $constructorParams,
        bool $expectedResult,
        array $expectedTupleKeys
    ): void {
        $metaData = new MetaData($classMetaData, $propertiesMetaData, $constructorParams);

        self::assertSame($expectedResult, $metaData->isInstantiableByConstructor());

        $tuples = $metaData->getPropertyWithConstructorParamTuples();

        self::assertCount(count($expectedTupleKeys), $tuples);

        foreach ($expectedTupleKeys as $key) {
            self::assertArrayHasKey($key, $tuples);
            self::assertInstanceOf(PropertyWithConstructorParamTupleInterface::class, $tuples[$key]);
        }

        $tuples2 = $metaData->getPropertyWithConstructorParamTuples();
        self::assertSame($tuples, $tuples2);
    }

    /**
     * @return array
     */
    public function mixedParamsAndConstructorParamsProvider(): array
    {
        $classMetadata = $this->getMockBuilder(ClassMetaDataInterface::class)->getMock();
        $propertyMetaData = $this->getMockBuilder(PropertyMetaDataInterface::class)->getMock();
        $constrParamMetaData = $this->getMockBuilder(ConstructorParamMetaDataInterface::class)->getMock();

        return [
            [
                $classMetadata,
                [
                    'test1' => $propertyMetaData,
                    'test2' => $propertyMetaData,
                    'test3' => $propertyMetaData,
                ],
                [
                    'test1' => $constrParamMetaData,
                    'test2' => $constrParamMetaData,
                    'test3' => $constrParamMetaData,
                ],
                true,
                ['test1', 'test2', 'test3'],
            ],
            [
                $classMetadata,
                [
                    'test3' => $propertyMetaData,
                    'test2' => $propertyMetaData,
                    'test1' => $propertyMetaData,
                ],
                [
                    'test1' => $constrParamMetaData,
                    'test2' => $constrParamMetaData,
                    'test3' => $constrParamMetaData,
                ],
                true,
                ['test1', 'test2', 'test3'],
            ],
            [
                $classMetadata,
                [
                    'test1' => $propertyMetaData,
                    'test2' => $propertyMetaData,
                ],
                [
                    'test1' => $constrParamMetaData,
                    'test2' => $constrParamMetaData,
                    'test3' => $constrParamMetaData,
                ],
                false,
                [],
            ],
            [
                $classMetadata,
                [
                    'test1' => $propertyMetaData,
                    'test2' => $propertyMetaData,
                    'test3' => $propertyMetaData,
                ],
                [
                    'test1' => $constrParamMetaData,
                    'test2' => $constrParamMetaData,
                ],
                true,
                ['test1', 'test2'],
            ],
            [
                $classMetadata,
                [
                    'test1' => $propertyMetaData,
                    'test2' => $propertyMetaData,
                    'test3' => $propertyMetaData,
                ],
                [
                    'test4' => $constrParamMetaData,
                    'test1' => $constrParamMetaData,
                    'test2' => $constrParamMetaData,
                ],
                false,
                [],
            ],
        ];
    }
}

<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Model\PropertyTuple;

use BetterSerializer\DataBind\MetaData\Model\ConstructorParamModel\ConstructorParamMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class PropertyWithConstructorParamTupleTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Model\PropertyTuple
 */
class PropertyWithConstructorParamTupleTest extends TestCase
{

    /**
     *
     */
    public function testEverything(): void
    {
        $type = $this->getMockBuilder(TypeInterface::class)->getMock();
        $key = 'test';
        $propertyMetaData = $this->getMockBuilder(PropertyMetaDataInterface::class)->getMock();
        $propertyMetaData->expects(self::once())
            ->method('getType')
            ->willReturn($type);
        $propertyMetaData->expects(self::once())
            ->method('getOutputKey')
            ->willReturn($key);
        $constrParamMetaData = $this->getMockBuilder(ConstructorParamMetaDataInterface::class)->getMock();

        /* @var $propertyMetaData PropertyMetaDataInterface */
        /* @var $constrParamMetaData ConstructorParamMetaDataInterface */
        $tuple = new PropertyWithConstructorParamTuple($propertyMetaData, $constrParamMetaData);

        self::assertSame($propertyMetaData, $tuple->getPropertyMetaData());
        self::assertSame($constrParamMetaData, $tuple->getConstructorParamMetaData());
        self::assertSame($type, $tuple->getType());
        self::assertSame($key, $tuple->getOutputKey());
    }
}

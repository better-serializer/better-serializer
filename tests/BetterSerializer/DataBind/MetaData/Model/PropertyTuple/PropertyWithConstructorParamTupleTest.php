<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Model\PropertyTuple;

use BetterSerializer\DataBind\MetaData\Model\ConstructorParamModel\ConstructorParamMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class PropertyWithConstructorParamTupleTest extends TestCase
{

    /**
     *
     */
    public function testEverything(): void
    {
        $type = $this->createMock(TypeInterface::class);
        $propertyMetaData = $this->createMock(PropertyMetaDataInterface::class);
        $propertyMetaData->expects(self::once())
            ->method('getType')
            ->willReturn($type);
        $constrParamMetaData = $this->createMock(ConstructorParamMetaDataInterface::class);
        $tuple = new PropertyWithConstructorParamTuple($propertyMetaData, $constrParamMetaData);

        self::assertSame($propertyMetaData, $tuple->getPropertyMetaData());
        self::assertSame($constrParamMetaData, $tuple->getConstructorParamMetaData());
        self::assertSame($type, $tuple->getType());
    }
}

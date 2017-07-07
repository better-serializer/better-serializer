<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Model\ConstructorParamModel;

use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class ConstructorParamTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Model\ConstructorParamModel
 */
class ConstructorParamMetaDataTest extends TestCase
{

    /**
     *
     */
    public function testGetPropertiesWithCustomParamName(): void
    {
        $name = 'test';
        $type = $this->getMockBuilder(TypeInterface::class)->getMock();
        $propertyName = 'testProperty';

        /* @var $type TypeInterface */
        $constructorParam = new ConstructorParamMetaData($name, $type, $propertyName);

        self::assertSame($name, $constructorParam->getName());
        self::assertSame($type, $constructorParam->getType());
        self::assertSame($propertyName, $constructorParam->getPropertyName());
    }

    /**
     *
     */
    public function testGetPropertiesWithSameParamName(): void
    {
        $name = 'test';
        $type = $this->getMockBuilder(TypeInterface::class)->getMock();

        /* @var $type TypeInterface */
        $constructorParam = new ConstructorParamMetaData($name, $type);

        self::assertSame($name, $constructorParam->getName());
        self::assertSame($type, $constructorParam->getType());
        self::assertSame($name, $constructorParam->getPropertyName());
    }
}

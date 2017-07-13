<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Constructor\Factory;

use BetterSerializer\DataBind\MetaData\Model\ClassModel\ClassMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\MetaDataInterface;
use BetterSerializer\DataBind\Reader\Constructor\UnserializeConstructor;
use PHPUnit\Framework\TestCase;

/**
 * Class UnserializeFactoryTest
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Constructor\Factory
 */
class UnserializeFactoryTest extends TestCase
{

    /**
     *
     */
    public function testNewConstructor(): void
    {
        $classMetaData = $this->getMockBuilder(ClassMetaDataInterface::class)->getMock();
        $classMetaData->expects(self::once())
            ->method('getClassName');

        $metaData = $this->getMockBuilder(MetaDataInterface::class)->getMock();
        $metaData->expects(self::once())
            ->method('getClassMetaData')
            ->willReturn($classMetaData);

        /* @var $metaData MetaDataInterface */
        $factory = new UnserializeConstructorFactory();
        $constructor = $factory->newConstructor($metaData);

        self::assertInstanceOf(UnserializeConstructor::class, $constructor);
    }
}

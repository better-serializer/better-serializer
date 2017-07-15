<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Instantiator\Factory\Deserialize;

use BetterSerializer\DataBind\MetaData\Model\ClassModel\ClassMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Model\MetaDataInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Deserialize\DeserializeInstantiator;
use BetterSerializer\DataBind\Reader\Instantiator\Factory\InstantiatorResultInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class UnserializeFactoryTest
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Instantiator\Factory
 */
class DeserializeInstantiatorFactoryTest extends TestCase
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
        $factory = new DeserializeInstantiatorFactory();
        $result = $factory->newInstantiator($metaData);

        self::assertInstanceOf(InstantiatorResultInterface::class, $result);
        self::assertInstanceOf(DeserializeInstantiator::class, $result->getInstantiator());
        self::assertTrue($factory->isApplicable($metaData));
    }
}

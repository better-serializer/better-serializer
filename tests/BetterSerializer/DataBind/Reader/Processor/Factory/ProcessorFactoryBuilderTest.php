<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory;

use BetterSerializer\DataBind\MetaData\Reader\ReaderInterface;
use BetterSerializer\DataBind\Reader\Constructor\Factory\ConstructorFactoryInterface;
use BetterSerializer\DataBind\Reader\Injector\Factory\AbstractFactoryInterface as InjectorFactoryInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class ProcessorFactoryBuilderTest
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Processor\Factory
 */
class ProcessorFactoryBuilderTest extends TestCase
{

    /**
     *
     */
    public function testBuild(): void
    {
        $constructorFactory = $this->getMockBuilder(ConstructorFactoryInterface::class)->getMock();
        $injectorFactory = $this->getMockBuilder(InjectorFactoryInterface::class)->getMock();
        $metaDataReader = $this->getMockBuilder(ReaderInterface::class)->getMock();

        /* @var $constructorFactory ConstructorFactoryInterface */
        /* @var $injectorFactory InjectorFactoryInterface */
        /* @var $metaDataReader ReaderInterface */
        $builder = new ProcessorFactoryBuilder($constructorFactory, $injectorFactory, $metaDataReader);
        $factory = $builder->build();

        self::assertInstanceOf(ProcessorFactoryInterface::class, $factory);
    }
}

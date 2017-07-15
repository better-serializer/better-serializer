<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory;

use BetterSerializer\DataBind\Converter\Factory\ConverterFactoryInterface;
use BetterSerializer\DataBind\MetaData\Reader\ReaderInterface;
use BetterSerializer\DataBind\Reader\Instantiator\Factory\ChainedInstantiatorFactoryInterface;
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
        $injectorFactory = $this->getMockBuilder(InjectorFactoryInterface::class)->getMock();
        $converterFactory = $this->getMockBuilder(ConverterFactoryInterface::class)->getMock();
        $metaDataReader = $this->getMockBuilder(ReaderInterface::class)->getMock();

        /* @var $instantiatorFactory ChainedInstantiatorFactoryInterface */
        /* @var $injectorFactory InjectorFactoryInterface */
        /* @var $converterFactory ConverterFactoryInterface */
        /* @var $metaDataReader ReaderInterface */
        $builder = new ProcessorFactoryBuilder(
            $converterFactory,
            $injectorFactory,
            $metaDataReader
        );
        $factory = $builder->build();

        self::assertInstanceOf(ProcessorFactoryInterface::class, $factory);
    }
}

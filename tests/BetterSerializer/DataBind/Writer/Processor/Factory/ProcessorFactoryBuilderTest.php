<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory;

use BetterSerializer\DataBind\Writer\Converter\ConverterFactoryInterface;
use BetterSerializer\DataBind\Writer\Extractor\Factory\AbstractFactoryInterface as ExtractorFactoryInterface;
use BetterSerializer\DataBind\Writer\MetaData\ContextualReaderInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class ProcessorFactoryBuilderTest
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor\Factory
 */
class ProcessorFactoryBuilderTest extends TestCase
{

    /**
     *
     */
    public function testBuild(): void
    {
        $converterFactory = $this->createMock(ConverterFactoryInterface::class);
        $extractorFactory = $this->createMock(ExtractorFactoryInterface::class);
        $metaDataReader = $this->createMock(ContextualReaderInterface::class);

        $builder = new ProcessorFactoryBuilder($converterFactory, $extractorFactory, $metaDataReader);
        $factory = $builder->build();

        self::assertInstanceOf(ProcessorFactoryInterface::class, $factory);
    }
}

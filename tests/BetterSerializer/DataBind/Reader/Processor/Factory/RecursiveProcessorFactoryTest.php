<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Reader\Processor\PropertyProcessorInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class RecursiveProcessorFactoryTest
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Processor\Factory
 */
class RecursiveProcessorFactoryTest extends TestCase
{

    /**
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function testCreateFromType(): void
    {
        $type = $this->createMock(TypeInterface::class);
        $type->method('__toString')
            ->willReturn('testType');

        $propertyMetaData = $this->createMock(PropertyMetaDataInterface::class);
        $propertyMetaData->method('getType')
            ->willReturn($type);
        $propertyMetaData->method('getOutputKey')
            ->willReturn('key');

        $nestedProcessor = $this->createMock(PropertyProcessorInterface::class);
        $nestedProcessor->expects(self::once())
            ->method('resolveRecursiveProcessors');

        $nestedFactory = $this->createMock(ProcessorFactoryInterface::class);

        $processorFactory = new RecursiveProcessorFactory($nestedFactory);

        $nestedFactory->method('createFromMetaData')
            ->with($propertyMetaData)
            ->willReturnCallback(
                function (PropertyMetaDataInterface $propMetaData) use ($processorFactory, $type, $nestedProcessor) {
                    $processorFactory->createFromType($type);

                    return $nestedProcessor;
                }
            );

        $nestedFactory->method('createFromType')
            ->with($type)
            ->willReturnCallback(
                function (TypeInterface $type) use ($processorFactory, $propertyMetaData) {
                    return $processorFactory->createFromMetaData($propertyMetaData);
                }
            );

        $processor = $processorFactory->createFromType($type);

        self::assertSame($nestedProcessor, $processor);
    }
}

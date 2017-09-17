<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory;

use BetterSerializer\DataBind\MetaData\Annotations\Groups;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Writer\Processor\ComplexNestedProcessorInterface;
use BetterSerializer\DataBind\Writer\SerializationContextInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class RecursiveProcessorFactoryTest
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor\Factory
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

        $nestedProcessor = $this->createMock(ComplexNestedProcessorInterface::class);
        $nestedProcessor->expects(self::once())
            ->method('resolveRecursiveProcessors');

        $nestedFactory = $this->createMock(ProcessorFactoryInterface::class);
        $context = $this->createMock(SerializationContextInterface::class);
        $context->method('getGroups')
            ->willReturn([Groups::DEFAULT_GROUP]);

        $processorFactory = new RecursiveProcessorFactory($nestedFactory);

        $nestedFactory->method('createFromMetaData')
            ->with($propertyMetaData)
            ->willReturnCallback(
                function (PropertyMetaDataInterface $propMetaData) use (
                    $processorFactory,
                    $type,
                    $nestedProcessor,
                    $context
                ) {
                    $processorFactory->createFromType($type, $context);

                    return $nestedProcessor;
                }
            );

        $nestedFactory->method('createFromType')
            ->with($type)
            ->willReturnCallback(
                function (TypeInterface $type) use ($processorFactory, $propertyMetaData, $context) {
                    return $processorFactory->createFromMetaData($propertyMetaData, $context);
                }
            );

        $processor = $processorFactory->createFromType($type, $context);

        self::assertSame($nestedProcessor, $processor);
    }
}

<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain\ChainMemberInterface as MetaDataMember;
use BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain\ChainMemberInterface as TypeMember;
use BetterSerializer\DataBind\Writer\Processor\ProcessorInterface;
use BetterSerializer\DataBind\Writer\SerializationContextInterface;
use PHPUnit\Framework\TestCase;
use LogicException;

/**
 * Class ProcessorFactoryTest
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor\Factory
 * @SuppressWarnings(PHPMD)
 */
class ProcessorFactoryTest extends TestCase
{

    /**
     *
     */
    public function testCreateFromMetaData(): void
    {
        $metaData = $this->createMock(PropertyMetaDataInterface::class);
        $processor = $this->createMock(ProcessorInterface::class);

        $chainMember = $this->createMock(MetaDataMember::class);
        $chainMember->expects(self::exactly(2))
            ->method('create')
            ->with($metaData)
            ->willReturnOnConsecutiveCalls(null, $processor);

        $context = $this->createMock(SerializationContextInterface::class);

        $factory = new ProcessorFactory([$chainMember, $chainMember]);
        /* @var $metaData PropertyMetaDataInterface */
        $newProcessor = $factory->createFromMetaData($metaData, $context);

        self::assertSame($processor, $newProcessor);
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessageRegExp /Unknown type - '[a-zA-Z0-9_]+'+/
     */
    public function testCreateFromMetaDataThrowsException(): void
    {
        $type = $this->createMock(TypeInterface::class);
        $metaData = $this->createMock(PropertyMetaDataInterface::class);
        $metaData->expects(self::once())
            ->method('getType')
            ->willReturn($type);

        $chainMember = $this->createMock(MetaDataMember::class);
        $chainMember->expects(self::once())
            ->method('create')
            ->with($metaData)
            ->willReturn(null);

        $context = $this->createMock(SerializationContextInterface::class);

        $factory = new ProcessorFactory([$chainMember]);
        /* @var $metaData PropertyMetaDataInterface */
        $factory->createFromMetaData($metaData, $context);
    }

    /**
     *
     */
    public function testAddMetaDataChainMember(): void
    {
        $type = $this->createMock(TypeInterface::class);
        $metaData = $this->createMock(PropertyMetaDataInterface::class);
        $metaData->expects(self::once())
            ->method('getType')
            ->willReturn($type);
        $processor = $this->createMock(ProcessorInterface::class);

        $chainMember = $this->createMock(MetaDataMember::class);
        $chainMember->expects(self::once())
            ->method('create')
            ->with($metaData)
            ->willReturn($processor);

        $context = $this->createMock(SerializationContextInterface::class);

        $factory = new ProcessorFactory();

        try {
            /* @var $metaData PropertyMetaDataInterface */
            $factory->createFromMetaData($metaData, $context);
        } catch (LogicException $e) {
        }

        /* @var $chainMember MetaDataMember */
        $factory->addMetaDataChainMember($chainMember);
        $newProcessor = $factory->createFromMetaData($metaData, $context);

        self::assertSame($processor, $newProcessor);
    }

    /**
     *
     */
    public function testCreateFromType(): void
    {
        $type = $this->createMock(TypeInterface::class);
        $processor = $this->createMock(ProcessorInterface::class);

        $chainMember = $this->createMock(TypeMember::class);
        $chainMember->expects(self::exactly(2))
            ->method('create')
            ->with($type)
            ->willReturnOnConsecutiveCalls(null, $processor);

        $context = $this->createMock(SerializationContextInterface::class);

        $factory = new ProcessorFactory([], [$chainMember, $chainMember]);
        $newProcessor = $factory->createFromType($type, $context);

        self::assertSame($processor, $newProcessor);
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessageRegExp /Unknown type - '[a-zA-Z0-9_]+'+/
     */
    public function testCreateFromTypeThrowsException(): void
    {
        $type = $this->createMock(TypeInterface::class);

        $chainMember = $this->createMock(TypeMember::class);
        $chainMember->expects(self::once())
            ->method('create')
            ->with($type)
            ->willReturn(null);

        $context = $this->createMock(SerializationContextInterface::class);

        $factory = new ProcessorFactory([], [$chainMember]);
        /* @var $type TypeInterface */
        $factory->createFromType($type, $context);
    }

    /**
     *
     */
    public function testAddTypeChainMember(): void
    {
        $type = $this->createMock(TypeInterface::class);
        $processor = $this->createMock(ProcessorInterface::class);

        $chainMember = $this->createMock(TypeMember::class);
        $chainMember->expects(self::once())
            ->method('create')
            ->with($type)
            ->willReturn($processor);

        $context = $this->createMock(SerializationContextInterface::class);

        $factory = new ProcessorFactory();

        try {
            /* @var $type TypeInterface */
            $factory->createFromType($type, $context);
        } catch (LogicException $e) {
        }

        /* @var $chainMember TypeMember */
        $factory->addTypeChainMember($chainMember);
        $newProcessor = $factory->createFromType($type, $context);

        self::assertSame($processor, $newProcessor);
    }
}

<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory;

use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Reader\Processor\Factory\PropertyMetaDataChain\ChainMemberInterface as MetaDataMember;
use BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain\ChainMemberInterface as TypeMember;
use BetterSerializer\DataBind\Reader\Processor\ProcessorInterface;
use PHPUnit\Framework\TestCase;
use LogicException;

/**
 * Class ProcessorFactoryTest
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Processor\Factory
 * @SuppressWarnings(PHPMD)
 */
class ProcessorFactoryTest extends TestCase
{

    /**
     *
     */
    public function testCreateFromMetaData(): void
    {
        $metaData = $this->getMockBuilder(PropertyMetaDataInterface::class)->getMock();
        $processor = $this->getMockBuilder(ProcessorInterface::class)->getMock();

        $chainMember = $this->getMockBuilder(MetaDataMember::class)->getMock();
        $chainMember->expects(self::exactly(2))
            ->method('create')
            ->with($metaData)
            ->willReturnOnConsecutiveCalls(null, $processor);

        $factory = new ProcessorFactory([$chainMember, $chainMember]);
        /* @var $metaData PropertyMetaDataInterface */
        $newProcessor = $factory->createFromMetaData($metaData);

        self::assertSame($processor, $newProcessor);
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessageRegExp /Unknown type - '[a-zA-Z0-9_]+'+/
     */
    public function testCreateFromMetaDataThrowsException(): void
    {
        $type = $this->getMockBuilder(TypeInterface::class)->getMock();
        $metaData = $this->getMockBuilder(PropertyMetaDataInterface::class)->getMock();
        $metaData->expects(self::once())
            ->method('getType')
            ->willReturn($type);

        $chainMember = $this->getMockBuilder(MetaDataMember::class)->getMock();
        $chainMember->expects(self::once())
            ->method('create')
            ->with($metaData)
            ->willReturn(null);

        $factory = new ProcessorFactory([$chainMember]);
        /* @var $metaData PropertyMetaDataInterface */
        $factory->createFromMetaData($metaData);
    }

    /**
     *
     */
    public function testAddMetaDataChainMember(): void
    {
        $type = $this->getMockBuilder(TypeInterface::class)->getMock();
        $metaData = $this->getMockBuilder(PropertyMetaDataInterface::class)->getMock();
        $metaData->expects(self::once())
            ->method('getType')
            ->willReturn($type);
        $processor = $this->getMockBuilder(ProcessorInterface::class)->getMock();

        $chainMember = $this->getMockBuilder(MetaDataMember::class)->getMock();
        $chainMember->expects(self::once())
            ->method('create')
            ->with($metaData)
            ->willReturn($processor);

        $factory = new ProcessorFactory();

        try {
            /* @var $metaData PropertyMetaDataInterface */
            $factory->createFromMetaData($metaData);
        } catch (LogicException $e) {
        }

        /* @var $chainMember MetaDataMember */
        $factory->addMetaDataChainMember($chainMember);
        $newProcessor = $factory->createFromMetaData($metaData);

        self::assertSame($processor, $newProcessor);
    }

    /**
     *
     */
    public function testCreateFromType(): void
    {
        $type = $this->getMockBuilder(TypeInterface::class)->getMock();
        $processor = $this->getMockBuilder(ProcessorInterface::class)->getMock();

        $chainMember = $this->getMockBuilder(TypeMember::class)->getMock();
        $chainMember->expects(self::exactly(2))
            ->method('create')
            ->with($type)
            ->willReturnOnConsecutiveCalls(null, $processor);

        $factory = new ProcessorFactory([], [$chainMember, $chainMember]);
        /* @var $type TypeInterface */
        $newProcessor = $factory->createFromType($type);

        self::assertSame($processor, $newProcessor);
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessageRegExp /Unknown type - '[a-zA-Z0-9_]+'+/
     */
    public function testCreateFromTypeThrowsException(): void
    {
        $type = $this->getMockBuilder(TypeInterface::class)->getMock();

        $chainMember = $this->getMockBuilder(TypeMember::class)->getMock();
        $chainMember->expects(self::once())
            ->method('create')
            ->with($type)
            ->willReturn(null);

        $factory = new ProcessorFactory([], [$chainMember]);
        /* @var $type TypeInterface */
        $factory->createFromType($type);
    }

    /**
     *
     */
    public function testAddTypeChainMember(): void
    {
        $type = $this->getMockBuilder(TypeInterface::class)->getMock();
        $processor = $this->getMockBuilder(ProcessorInterface::class)->getMock();

        $chainMember = $this->getMockBuilder(TypeMember::class)->getMock();
        $chainMember->expects(self::once())
            ->method('create')
            ->with($type)
            ->willReturn($processor);

        $factory = new ProcessorFactory();

        try {
            /* @var $type TypeInterface */
            $factory->createFromType($type);
        } catch (LogicException $e) {
        }

        /* @var $chainMember TypeMember */
        $factory->addTypeChainMember($chainMember);
        $newProcessor = $factory->createFromType($type);

        self::assertSame($processor, $newProcessor);
    }
}

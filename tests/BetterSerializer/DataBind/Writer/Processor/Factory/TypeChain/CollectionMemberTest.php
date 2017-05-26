<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain;

use BetterSerializer\DataBind\MetaData\Type\ArrayType;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Writer\Processor\Collection;
use BetterSerializer\DataBind\Writer\Processor\Factory\ProcessorFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\ProcessorInterface;
use PHPUnit\Framework\TestCase;
use Mockery;

/**
 * Class ArrayMemberTest
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CollectionMemberTest extends TestCase
{

    /**
     *
     */
    protected function tearDown()
    {
        Mockery::close();
    }

    /**
     *
     */
    public function testCreate(): void
    {
        $nestedType = Mockery::mock(TypeInterface::class);
        $arrayType = new ArrayType($nestedType);
        $processor = Mockery::mock(ProcessorInterface::class);

        $processorFactory = Mockery::mock(ProcessorFactoryInterface::class);
        $processorFactory->shouldReceive('createFromType')
            ->once()
            ->with($nestedType)
            ->andReturn($processor)
            ->getMock();

        /* @var $processorFactory ProcessorFactoryInterface */
        $collectionMember = new CollectionMember($processorFactory);
        $collectionProcessor = $collectionMember->create($arrayType);

        self::assertInstanceOf(Collection::class, $collectionProcessor);
    }

    /**
     *
     */
    public function testCreateReturnsNull(): void
    {
        $nonArrayType = Mockery::mock(TypeInterface::class);
        $processorFactory = Mockery::mock(ProcessorFactoryInterface::class);

        /* @var $processorFactory ProcessorFactoryInterface */
        $collectionMember = new CollectionMember($processorFactory);
        /* @var  $nonArrayType TypeInterface */
        $shouldBeNull = $collectionMember->create($nonArrayType);

        self::assertNull($shouldBeNull);
    }
}
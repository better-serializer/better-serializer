<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory;

use BetterSerializer\DataBind\MetaData\Annotations\Groups;
use BetterSerializer\DataBind\MetaData\Model\PropertyModel\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain\ChainMemberInterface as MetaDataMember;
use BetterSerializer\DataBind\Writer\Processor\Factory\TypeChain\ChainMemberInterface as TypeMember;
use BetterSerializer\DataBind\Writer\Processor\ProcessorInterface;
use BetterSerializer\DataBind\Writer\SerializationContextInterface;
use Doctrine\Common\Cache\Cache;
use PHPUnit\Framework\TestCase;

/**
 * Class CachedProcessorFactoryTest
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor\Factory
 */
class CachedProcessorFactoryTest extends TestCase
{

    /**
     *
     */
    public function testCreateFromType(): void
    {
        $typeToString = 'test';

        $type = $this->createMock(TypeInterface::class);
        $type->method('__toString')
            ->willReturn($typeToString);

        $processor = $this->createMock(ProcessorInterface::class);

        $nestedFactory = $this->createMock(ProcessorFactoryInterface::class);
        $nestedFactory->expects(self::once())
            ->method('createFromType')
            ->with($type)
            ->willReturn($processor);

        $cache = $this->createMock(Cache::class);
        $cache->expects(self::exactly(2))
            ->method('contains')
            ->with(self::anything())
            ->willReturnOnConsecutiveCalls(false, true);
        $cache->expects(self::once())
            ->method('fetch')
            ->with(self::anything())
            ->willReturn($processor);
        $cache->expects(self::once())
            ->method('save')
            ->with(self::anything(), $processor);

        $context = $this->createMock(SerializationContextInterface::class);
        $context->expects(self::exactly(2))
            ->method('getGroups')
            ->willReturn([Groups::DEFAULT_GROUP]);

        $factory = new CachedProcessorFactory($nestedFactory, $cache);
        $createdProcessor1 = $factory->createFromType($type, $context);

        self::assertSame($processor, $createdProcessor1);

        $createdProcessor2 = $factory->createFromType($type, $context);
        self::assertSame($processor, $createdProcessor2);
    }

    /**
     *
     */
    public function testEverythingElse(): void
    {
        $metaData = $this->createMock(PropertyMetaDataInterface::class);
        $processor = $this->createMock(ProcessorInterface::class);
        $metaDataMember = $this->createMock(MetaDataMember::class);
        $typeMember = $this->createMock(TypeMember::class);

        $nestedFactory = $this->createMock(ProcessorFactoryInterface::class);
        $nestedFactory->expects(self::once())
            ->method('createFromMetaData')
            ->with($metaData)
            ->willReturn($processor);
        $nestedFactory->expects(self::once())
            ->method('addMetaDataChainMember')
            ->with($metaDataMember);
        $nestedFactory->expects(self::once())
            ->method('addTypeChainMember')
            ->with($typeMember);

        $cache = $this->createMock(Cache::class);

        $context = $this->createMock(SerializationContextInterface::class);

        $factory = new CachedProcessorFactory($nestedFactory, $cache);
        $createdProcessor = $factory->createFromMetaData($metaData, $context);

        self::assertSame($processor, $createdProcessor);

        $factory->addMetaDataChainMember($metaDataMember);
        $factory->addTypeChainMember($typeMember);
    }
}

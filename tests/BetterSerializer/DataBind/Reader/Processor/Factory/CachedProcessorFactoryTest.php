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
use Doctrine\Common\Cache\Cache;
use PHPUnit\Framework\TestCase;

/**
 * Class CachedProcessorFactoryTest
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Processor\Factory
 */
class CachedProcessorFactoryTest extends TestCase
{

    /**
     *
     */
    public function testCreateFromType(): void
    {
        $typeToString = 'test';
        $cacheKey = 'reader||processor||' . $typeToString;

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
            ->with($cacheKey)
            ->willReturnOnConsecutiveCalls(false, true);
        $cache->expects(self::once())
            ->method('fetch')
            ->with($cacheKey)
            ->willReturn($processor);
        $cache->expects(self::once())
            ->method('save')
            ->with($cacheKey, $processor);

        $factory = new CachedProcessorFactory($nestedFactory, $cache);
        $createdProcessor1 = $factory->createFromType($type);

        self::assertSame($processor, $createdProcessor1);

        $createdProcessor2 = $factory->createFromType($type);
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

        $factory = new CachedProcessorFactory($nestedFactory, $cache);
        $createdProcessor = $factory->createFromMetaData($metaData);

        self::assertSame($processor, $createdProcessor);

        $factory->addMetaDataChainMember($metaDataMember);
        $factory->addTypeChainMember($typeMember);
    }
}

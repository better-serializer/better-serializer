<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain;

use BetterSerializer\DataBind\MetaData\PropertyMetaDataInterface;
use BetterSerializer\DataBind\MetaData\Type\ObjectType;
use BetterSerializer\DataBind\MetaData\Type\StringType;
use BetterSerializer\DataBind\Writer\Extractor\ExtractorInterface;
use BetterSerializer\DataBind\Writer\Extractor\Factory\AbstractFactoryInterface as ExtractorFactoryInterface;
use BetterSerializer\DataBind\Writer\Processor\Property;
use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;
use Mockery;

/**
 * Class SimpleMemberTest
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Processor\Factory\PropertyMetaDataChain
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class SimpleMemberTest extends TestCase
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
        $type = new StringType();
        $propertyMetaData = Mockery::mock(PropertyMetaDataInterface::class);
        $propertyMetaData->shouldReceive('getType')
            ->once()
            ->andReturn($type)
            ->getMock()
            ->shouldReceive('getOutputKey')
            ->once()
            ->andReturn('test')
            ->getMock();

        $extractor = Mockery::mock(ExtractorInterface::class);

        $extractorFactory = Mockery::mock(ExtractorFactoryInterface::class);
        $extractorFactory->shouldReceive('newExtractor')
            ->once()
            ->andReturn($extractor)
            ->getMock();

        $simpleMember = new SimpleMember($extractorFactory);
        $processor = $simpleMember->create($propertyMetaData);

        self::assertInstanceOf(Property::class, $processor);
    }

    /**
     *
     */
    public function testCreateReturnsNull(): void
    {
        $type = new ObjectType(Car::class);
        $propertyMetaData = Mockery::mock(PropertyMetaDataInterface::class);
        $propertyMetaData->shouldReceive('getType')
            ->once()
            ->andReturn($type)
            ->getMock();

        $extractorFactory = Mockery::mock(ExtractorFactoryInterface::class);

        $simpleMember = new SimpleMember($extractorFactory);
        $shouldBeNull = $simpleMember->create($propertyMetaData);

        self::assertNull($shouldBeNull);
    }
}

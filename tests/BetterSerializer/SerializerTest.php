<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer;

use BetterSerializer\Common\SerializationType;
use BetterSerializer\DataBind\Writer\WriterInterface;
use BetterSerializer\Dto\Radio;
use PHPUnit\Framework\TestCase;
use Mockery;

/**
 * Class ObjectMapperTest
 * @author mfris
 * @package BetterSerializer\DataBind
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class SerializerTest extends TestCase
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
    public function testWriteValueAsString(): void
    {
        $toSerialize = new Radio('test');
        $serializationType = SerializationType::NONE();
        $serializedData = 'serialized';

        $writer = Mockery::mock(WriterInterface::class);
        $writer->shouldReceive('writeValueAsString')
            ->with($toSerialize, $serializationType)
            ->once()
            ->andReturn($serializedData)
            ->getMock();

        $serializer = new Serializer($writer);
        $output = $serializer->writeValueAsString($toSerialize, $serializationType);

        self::assertSame($serializedData, $output);
    }
}

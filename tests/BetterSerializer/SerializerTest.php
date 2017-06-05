<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer;

use BetterSerializer\Common\SerializationTypeInterface;
use BetterSerializer\DataBind\Reader\ReaderInterface;
use BetterSerializer\DataBind\Writer\WriterInterface;
use BetterSerializer\Dto\CarInterface;
use PHPUnit\Framework\TestCase;

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
    public function testReadValueFromString(): void
    {
        $toDeserialize = '{"a":"a"}';
        $serializationType = $this->getMockBuilder(SerializationTypeInterface::class)->getMock();
        $stringType = CarInterface::class;
        $desertializedData = $this->getMockBuilder(CarInterface::class)->getMock();

        $reader = $this->getMockBuilder(ReaderInterface::class)->getMock();
        $reader->expects(self::once())
            ->method('readValue')
            ->with($toDeserialize, $stringType, $serializationType)
            ->willReturn($desertializedData);

        $writer = $this->getMockBuilder(WriterInterface::class)->getMock();

        /* @var $reader ReaderInterface */
        /* @var $writer WriterInterface */
        /* @var $serializationType SerializationTypeInterface */
        $serializer = new Serializer($reader, $writer);
        $output = $serializer->readValueFromString($toDeserialize, $stringType, $serializationType);

        self::assertSame($desertializedData, $output);
    }

    /**
     *
     */
    public function testWriteValueAsString(): void
    {
        $toSerialize = $this->getMockBuilder(CarInterface::class)->getMock();
        $serializationType = $this->getMockBuilder(SerializationTypeInterface::class)->getMock();
        $serializedData = 'serialized';

        $reader = $this->getMockBuilder(ReaderInterface::class)->getMock();

        $writer = $this->getMockBuilder(WriterInterface::class)->getMock();
        $writer->expects(self::once())
            ->method('writeValueAsString')
            ->with($toSerialize, $serializationType)
            ->willReturn($serializedData);

        /* @var $reader ReaderInterface */
        /* @var $writer WriterInterface */
        /* @var $serializationType SerializationTypeInterface */
        $serializer = new Serializer($reader, $writer);
        $output = $serializer->writeValueAsString($toSerialize, $serializationType);

        self::assertSame($serializedData, $output);
    }
}

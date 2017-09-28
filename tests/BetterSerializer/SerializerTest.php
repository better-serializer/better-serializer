<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer;

use BetterSerializer\Common\SerializationTypeInterface;
use BetterSerializer\DataBind\Reader\ReaderInterface;
use BetterSerializer\DataBind\Writer\SerializationContext;
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
        $serializationType = $this->createMock(SerializationTypeInterface::class);
        $stringType = CarInterface::class;
        $desertializedData = $this->createMock(CarInterface::class);

        $reader = $this->createMock(ReaderInterface::class);
        $reader->expects(self::once())
            ->method('readValue')
            ->with($toDeserialize, $stringType, $serializationType)
            ->willReturn($desertializedData);

        $writer = $this->createMock(WriterInterface::class);

        $serializer = new Serializer($reader, $writer);
        $output = $serializer->deserialize($toDeserialize, $stringType, $serializationType);

        self::assertSame($desertializedData, $output);
    }

    /**
     *
     */
    public function testWriteValueAsString(): void
    {
        $toSerialize = $this->createMock(CarInterface::class);
        $serializationType = $this->createMock(SerializationTypeInterface::class);
        $serializedData = 'serialized';

        $reader = $this->createMock(ReaderInterface::class);

        $writer = $this->createMock(WriterInterface::class);
        $writer->expects(self::once())
            ->method('writeValueAsString')
            ->with($toSerialize, $serializationType)
            ->willReturn($serializedData);

        $serializer = new Serializer($reader, $writer);
        $output = $serializer->serialize($toSerialize, $serializationType);

        self::assertSame($serializedData, $output);
    }
}

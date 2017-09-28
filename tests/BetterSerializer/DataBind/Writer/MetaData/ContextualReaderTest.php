<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\MetaData;

use BetterSerializer\DataBind\MetaData\Model\MetaDataInterface;
use BetterSerializer\DataBind\MetaData\Reader\ReaderInterface;
use BetterSerializer\DataBind\Writer\SerializationContextInterface;
use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;

/**
 * Class ContextualReaderTest
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\MetaData
 */
class ContextualReaderTest extends TestCase
{

    /**
     *
     */
    public function testRead(): void
    {
        $className = Car::class;

        $metaData = $this->createMock(MetaDataInterface::class);

        $nestedReader = $this->createMock(ReaderInterface::class);
        $nestedReader->expects(self::once())
            ->method('read')
            ->with($className)
            ->willReturn($metaData);

        $context = $this->createMock(SerializationContextInterface::class);

        $reader = new ContextualReader($nestedReader);
        $contextualMetaData = $reader->read($className, $context);

        self::assertInstanceOf(ContextualMetaData::class, $contextualMetaData);
    }
}

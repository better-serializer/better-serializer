<?php
/**
 * @author  mfris
 */
declare(strict_types = 1);

namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;

class ReaderFactoryTest extends TestCase
{

    public function testCreateReader(): void
    {
        $readerFactory = new ReaderFactory();
        $reader = $readerFactory->createReader();

        self::assertInstanceOf(Reader::class, $reader);
    }
}

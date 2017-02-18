<?php
declare(strict_types = 1);

/**
 * @author  mfris
 */
namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;

/**
 * Class ReaderFactoryTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 */
class ReaderFactoryTest extends TestCase
{

    public function testCreateReader(): void
    {
        $readerFactory = new ReaderFactory();
        $reader = $readerFactory->createReader();

        self::assertInstanceOf(Reader::class, $reader);
    }
}

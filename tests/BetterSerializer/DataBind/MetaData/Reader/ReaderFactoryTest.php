<?php
declare(strict_types = 1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\MetaData\Reader;

use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use phpDocumentor\Reflection\DocBlockFactoryInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class ReaderFactoryTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class ReaderFactoryTest extends TestCase
{

    /**
     *
     */
    public function testCreateReader(): void
    {
        $docBlockFactoryStub = $this->getMockBuilder(DocBlockFactoryInterface::class)->getMock();
        /* @var $docBlockFactoryStub DocBlockFactoryInterface */

        $typeFactoryStub = $this->getMockBuilder(TypeFactoryInterface::class)->getMock();
        /* @var $typeFactoryStub TypeFactoryInterface */

        $readerFactory = new ReaderFactory($docBlockFactoryStub, $typeFactoryStub);
        $reader = $readerFactory->createReader();

        self::assertInstanceOf(Reader::class, $reader);

        // test cached reader
        $reader2 = $readerFactory->createReader();

        self::assertInstanceOf(Reader::class, $reader2);
        self::assertSame($reader, $reader2);
    }
}

<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Type;

use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Writer\Type\Chain\ChainMemberInterface;
use PHPUnit\Framework\TestCase;
use LogicException;

/**
 * Class ExtractorTest
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\Type
 */
class ExtractorTest extends TestCase
{

    /**
     *
     */
    public function testExtract(): void
    {
        $data = 'test';
        $type = $this->getMockBuilder(TypeInterface::class)->getMock();

        $chainMember = $this->getMockBuilder(ChainMemberInterface::class)->getMock();
        $chainMember->expects(self::exactly(2))
            ->method('getType')
            ->with($data)
            ->willReturnOnConsecutiveCalls(null, $type);

        $extractor = new Extractor([$chainMember, $chainMember]);
        $createdType = $extractor->extract($data);

        self::assertSame($type, $createdType);
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessageRegExp /Unknown type - '[a-zA-Z0-9]+'+/
     */
    public function testGetTypeThrowsException(): void
    {
        $data = 'test';
        $chainMember = $this->getMockBuilder(ChainMemberInterface::class)->getMock();
        $chainMember->expects(self::once())
            ->method('getType')
            ->with($data)
            ->willReturn(null);

        $extractor = new Extractor([$chainMember]);
        $extractor->extract($data);
    }

    /**
     *
     */
    public function testAddChainMemberType(): void
    {
        $data = 'test';
        $type = $this->getMockBuilder(TypeInterface::class)->getMock();

        $chainMember = $this->getMockBuilder(ChainMemberInterface::class)->getMock();
        $chainMember->expects(self::once())
            ->method('getType')
            ->with($data)
            ->willReturn($type);

        $extractor = new Extractor();

        try {
            $extractor->extract($data);
        } catch (LogicException $e) {
        }

        /* @var $chainMember ChainMemberInterface */
        $extractor->addChainMember($chainMember);
        $createdType = $extractor->extract($data);

        self::assertSame($type, $createdType);
    }
}

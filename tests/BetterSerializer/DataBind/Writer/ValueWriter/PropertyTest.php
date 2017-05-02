<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */
namespace BetterSerializer\DataBind\Writer\ValueWriter;

use BetterSerializer\DataBind\Writer\Context\ContextInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class PropertyTest
 * @author mfris
 * @package BetterSerializer\DataBind\Writer\ValueWriter
 */
class PropertyTest extends TestCase
{

    /**
     *
     */
    public function testWriteValue(): void
    {
        $key = 'key';
        $value = 'value';

        /* @var $contextStub \PHPUnit_Framework_MockObject_MockObject */
        $contextStub = $this->getMockBuilder(ContextInterface::class)->getMock();
        $contextStub->expects(self::once())
            ->method('write')
            ->with($key, $value);

        /* @var $contextStub ContextInterface */
        $writer = new Property($key);
        $writer->writeValue($contextStub, $value);
    }
}

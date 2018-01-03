<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor;

use BetterSerializer\Common\TypeExtensionInterface;
use BetterSerializer\DataBind\Writer\Context\ContextInterface;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class ExtensionProcessorTest extends TestCase
{

    /**
     *
     */
    public function testProcess(): void
    {
        $data = 4;
        $context = $this->createMock(ContextInterface::class);
        $objectExtension = $this->createMock(TypeExtensionInterface::class);
        $objectExtension->expects(self::once())
            ->method('appendData')
            ->with($context, $data);

        $processor = new ExtensionProcessor($objectExtension);
        $processor->process($context, $data);
    }
}

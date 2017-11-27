<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Processor;

use BetterSerializer\Common\CustomTypeExtensionInterface;
use BetterSerializer\DataBind\Writer\Context\ContextInterface;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class CustomTypeTest extends TestCase
{

    /**
     *
     */
    public function testProcess(): void
    {
        $key = 'test';
        $data = 4;
        $context = $this->createMock(ContextInterface::class);
        $objectExtension = $this->createMock(CustomTypeExtensionInterface::class);
        $objectExtension->expects(self::once())
            ->method('appendData')
            ->with($context, $key, $data);

        $processor = new CustomType($objectExtension, $key);
        $processor->process($context, $data);
    }
}

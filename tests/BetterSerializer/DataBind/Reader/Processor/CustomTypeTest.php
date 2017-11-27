<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor;

use BetterSerializer\Common\CustomTypeExtensionInterface;
use BetterSerializer\DataBind\Reader\Context\ContextInterface;
use BetterSerializer\DataBind\Reader\Injector\InjectorInterface;
use BetterSerializer\Dto\Car3;
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
        $object = new Car3();

        $context = $this->createMock(ContextInterface::class);
        $context->expects(self::once())
            ->method('getDeserialized')
            ->willReturn($object);
        $objectExtension = $this->createMock(CustomTypeExtensionInterface::class);
        $objectExtension->expects(self::once())
            ->method('extractData')
            ->with($context, $key)
            ->willReturn($data);

        $injector = $this->createMock(InjectorInterface::class);
        $injector->expects(self::once())
            ->method('inject')
            ->with($object, $data);

        $processor = new CustomType($injector, $objectExtension, $key);
        $processor->process($context);
    }
}

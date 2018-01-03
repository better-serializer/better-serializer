<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor;

use BetterSerializer\Common\TypeExtensionInterface;
use BetterSerializer\DataBind\Reader\Context\ContextInterface;
use BetterSerializer\DataBind\Reader\Injector\InjectorInterface;
use BetterSerializer\Dto\Car3;
use Doctrine\Common\Collections\ArrayCollection;
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
        $data = new Car3(new ArrayCollection());

        $context = $this->createMock(ContextInterface::class);
        $context->expects(self::once())
            ->method('setDeserialized')
            ->with($data);

        $objectExtension = $this->createMock(TypeExtensionInterface::class);
        $objectExtension->expects(self::once())
            ->method('extractData')
            ->with($context)
            ->willReturn($data);

        $processor = new ExtensionProcessor($objectExtension);
        $processor->process($context);
    }
}

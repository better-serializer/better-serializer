<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Processor\Factory\TypeChain;

use BetterSerializer\DataBind\MetaData\Type\ExtensionCollectionTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parameters\ParametersInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\DataBind\Reader\Processor\ExtensionCollectionProcessor;
use BetterSerializer\DataBind\Reader\Processor\Factory\ProcessorFactoryInterface;
use BetterSerializer\Helper\ExtensionMockFactory;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 *
 */
class ExtensionCollectionMemberTest extends TestCase
{

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testCreate(): void
    {
        $className = Collection::class;
        $customHandler = ExtensionMockFactory::createCollectionExtensionMock($className, true);
        $handlerClass = get_class($customHandler);

        $parameters = $this->createMock(ParametersInterface::class);
        $nestedType = $this->createMock(TypeInterface::class);
        $type = $this->createMock(ExtensionCollectionTypeInterface::class);
        $type->expects(self::exactly(2))
            ->method('getCustomType')
            ->willReturn($className);
        $type->expects(self::once())
            ->method('getNestedType')
            ->willReturn($nestedType);
        $type->expects(self::once())
            ->method('getParameters')
            ->willReturn($parameters);

        $processorFactory = $this->createMock(ProcessorFactoryInterface::class);

        $customObjectMember = new ExtensionCollectionMember($processorFactory, [$handlerClass]);
        $processor = $customObjectMember->create($type);

        self::assertInstanceOf(ExtensionCollectionProcessor::class, $processor);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Type handler [a-zA-Z0-9_\\]+ is missing the getType method\./
     */
    public function testCreateThrowsOnInvalidHandlerClass(): void
    {
        $processorFactory = $this->createMock(ProcessorFactoryInterface::class);

        new ExtensionCollectionMember($processorFactory, [Collection::class]);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Handler for class [a-zA-Z0-9_\\]+ is already registered\./
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function testCreateThrowsOnDuplicitHandlerRegistration(): void
    {
        $className = Collection::class;
        $customHandler1 = ExtensionMockFactory::createTypeExcensionMock($className);
        $handlerClass1 = get_class($customHandler1);
        $customHandler2 = ExtensionMockFactory::createTypeExcensionMock($className);
        $handlerClass2 = get_class($customHandler2);
        $processorFactory = $this->createMock(ProcessorFactoryInterface::class);

        $member = new ExtensionCollectionMember($processorFactory, [$handlerClass1]);
        $member->addCustomHandlerClass($handlerClass2);
    }
}

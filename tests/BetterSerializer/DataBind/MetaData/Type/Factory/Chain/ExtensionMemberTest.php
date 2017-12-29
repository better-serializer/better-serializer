<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\ExtensionObjectType;
use BetterSerializer\DataBind\MetaData\Type\ExtensionType;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\Parameters\ParametersInterface;
use BetterSerializer\DataBind\MetaData\Type\Parameters\ParserInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\StringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeEnum;
use BetterSerializer\Dto\Car;
use BetterSerializer\Dto\Radio;
use BetterSerializer\Helper\ExtensionMockFactory;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 *
 */
class ExtensionMemberTest extends TestCase
{

    /**
     * @SuppressWarnings(PHPMD)
     */
    public function testGetTypeWithCustomNonObject(): void
    {
        $customType = 'MyType';
        $extension = ExtensionMockFactory::createTypeExcensionMock($customType, TypeEnum::BOOLEAN);

        $processorClass = get_class($extension);
        $stringFormType = $this->createMock(StringFormTypeInterface::class);
        $stringFormType->method('getStringType')
            ->willReturn($customType);
        $stringFormType->method('isClass')
            ->willReturn(false);

        $parameters = $this->createMock(ParametersInterface::class);
        $parser = $this->createMock(ParserInterface::class);
        $parser->method('parseParameters')
            ->with($stringFormType)
            ->willReturn($parameters);

        $typeFactory = $this->createMock(TypeFactoryInterface::class);
        $typeFactory->expects(self::once())
            ->method('getType');

        $extensionMember = new ExtensionMember($typeFactory, $parser, [$processorClass]);
        $type = $extensionMember->getType($stringFormType);

        /* @var $type ExtensionType */
        self::assertInstanceOf(ExtensionType::class, $type);
        self::assertSame($customType, $type->getCustomType());
        self::assertSame($parameters, $type->getParameters());
    }

    /**
     * @SuppressWarnings(PHPMD)
     */
    public function testGetTypeWithCustomObject(): void
    {
        $customType = Car::class;
        $extension = ExtensionMockFactory::createTypeExcensionMock($customType);

        $processorClass = get_class($extension);
        $stringFormType = $this->createMock(StringFormTypeInterface::class);
        $stringFormType->method('getStringType')
            ->willReturn($customType);
        $stringFormType->method('isClassOrInterface')
            ->willReturn(true);

        $parameters = $this->createMock(ParametersInterface::class);
        $parser = $this->createMock(ParserInterface::class);
        $parser->method('parseParameters')
            ->with($stringFormType)
            ->willReturn($parameters);

        $typeFactory = $this->createMock(TypeFactoryInterface::class);
        $typeFactory->expects(self::never())
            ->method('getType');

        $extensionMember = new ExtensionMember($typeFactory, $parser, [$processorClass]);
        $type = $extensionMember->getType($stringFormType);

        /* @var $type ExtensionObjectType */
        self::assertInstanceOf(ExtensionObjectType::class, $type);
        self::assertSame($customType, $type->getCustomType());
        self::assertSame($parameters, $type->getParameters());
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Type handler [a-zA-Z0-9_\\]+ is missing the getType method\./
     */
    public function testCreateThrowsOnInvalidHandlerClass(): void
    {
        $parser = $this->createMock(ParserInterface::class);
        $typeFactory = $this->createMock(TypeFactoryInterface::class);
        $typeFactory->expects(self::never())
            ->method('getType');

        new ExtensionMember($typeFactory, $parser, [Car::class]);
    }

    /**
     * @SuppressWarnings(PHPMD)
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Handler for class [a-zA-Z0-9\\]+ is already registered\./
     */
    public function testAddCustomObjectClassThrowsRuntimeException(): void
    {
        $customObject = ExtensionMockFactory::createTypeExcensionMock(Car::class);

        $processorClass = get_class($customObject);
        $parser = $this->createMock(ParserInterface::class);
        $typeFactory = $this->createMock(TypeFactoryInterface::class);
        $typeFactory->expects(self::never())
            ->method('getType');

        new ExtensionMember($typeFactory, $parser, [$processorClass, $processorClass]);
    }

    /**
     *
     */
    public function testGetTypeWithoutRegisteredHandlersReturnsNull(): void
    {
        $typeFactory = $this->createMock(TypeFactoryInterface::class);
        $typeFactory->expects(self::never())
            ->method('getType');
        $parser = $this->createMock(ParserInterface::class);
        $stringFormType = $this->createMock(StringFormTypeInterface::class);

        $customObject = new ExtensionMember($typeFactory, $parser);
        $type = $customObject->getType($stringFormType);

        self::assertNull($type);
    }

    /**
     * @SuppressWarnings(PHPMD)
     */
    public function testGetTypeForWrongTypeDefinitionReturnsNull(): void
    {
        $customObject = ExtensionMockFactory::createTypeExcensionMock(Car::class);

        $typeFactory = $this->createMock(TypeFactoryInterface::class);
        $typeFactory->expects(self::never())
            ->method('getType');
        $parser = $this->createMock(ParserInterface::class);
        $stringFormType = $this->createMock(StringFormTypeInterface::class);
        $stringFormType->method('isClass')
            ->willReturn(true);
        $stringFormType->method('getStringType')
            ->willReturn('!234');

        $customObject = new ExtensionMember($typeFactory, $parser, [get_class($customObject)]);
        $type = $customObject->getType($stringFormType);

        self::assertNull($type);
    }

    /**
     * @SuppressWarnings(PHPMD)
     */
    public function testGetTypeForUnsupportedCustomObjectReturnsNull(): void
    {
        $customObject = ExtensionMockFactory::createTypeExcensionMock(Car::class);

        $typeFactory = $this->createMock(TypeFactoryInterface::class);
        $typeFactory->expects(self::never())
            ->method('getType');
        $parser = $this->createMock(ParserInterface::class);
        $stringFormType = $this->createMock(StringFormTypeInterface::class);
        $stringFormType->method('isClass')
            ->willReturn(true);
        $stringFormType->method('getStringType')
            ->willReturn(Radio::class);

        $customObject = new ExtensionMember($typeFactory, $parser, [get_class($customObject)]);
        $type = $customObject->getType($stringFormType);

        self::assertNull($type);
    }
}

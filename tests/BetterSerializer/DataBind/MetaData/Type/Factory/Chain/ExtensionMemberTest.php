<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\ExtensionClassType;
use BetterSerializer\DataBind\MetaData\Type\ExtensionType;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\ContextStringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parameters\ParametersInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\StringTypeParserInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeClassEnum;
use BetterSerializer\DataBind\MetaData\Type\TypeClassEnumInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeEnum;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\Dto\Car;
use BetterSerializer\Dto\Radio;
use BetterSerializer\Helper\ExtensionMockFactory;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ExtensionMemberTest extends TestCase
{

    /**
     * @SuppressWarnings(PHPMD)
     */
    public function testGetTypeWithCustomNonObject(): void
    {
        $customType = 'MyType';
        $typeClass = $this->createMock(TypeClassEnumInterface::class);
        $replacedTypeString = TypeEnum::BOOLEAN_TYPE;
        $extension = ExtensionMockFactory::createTypeExcensionMock($customType, $replacedTypeString);

        $processorClass = get_class($extension);
        $stringFormType = $this->createMock(ContextStringFormTypeInterface::class);
        $stringFormType->method('getStringType')
            ->willReturn($customType);
        $stringFormType->method('getTypeClass')
            ->willReturn($typeClass);

        $replStringFormType = $this->createMock(ContextStringFormTypeInterface::class);
        $stringTypeParser = $this->createMock(StringTypeParserInterface::class);
        $stringTypeParser->method('parseSimple')
            ->with($replacedTypeString)
            ->willReturn($replStringFormType);

        $replacedType = $this->createMock(TypeInterface::class);
        $typeFactory = $this->createMock(TypeFactoryInterface::class);
        $typeFactory->expects(self::once())
            ->method('getType')
            ->with($replStringFormType)
            ->willReturn($replacedType);

        $extensionMember = new ExtensionMember($typeFactory, $stringTypeParser, [$processorClass]);
        $type = $extensionMember->getType($stringFormType);

        /* @var $type ExtensionType */
        self::assertNotNull($type);
        self::assertInstanceOf(ExtensionType::class, $type);
        self::assertSame($customType, $type->getCustomType());
        self::assertInstanceOf(ParametersInterface::class, $type->getParameters());
    }

    /**
     * @SuppressWarnings(PHPMD)
     */
    public function testGetTypeWithCustomObject(): void
    {
        $customType = Car::class;
        $extension = ExtensionMockFactory::createTypeExcensionMock($customType);

        $processorClass = get_class($extension);
        $typeClass = TypeClassEnum::CLASS_TYPE();
        $parameters = $this->createMock(ParametersInterface::class);
        $stringFormType = $this->createMock(ContextStringFormTypeInterface::class);
        $stringFormType->method('getStringType')
            ->willReturn($customType);
        $stringFormType->method('getTypeClass')
            ->willReturn($typeClass);
        $stringFormType->method('getParameters')
            ->willReturn($parameters);

        $stringTypeParser = $this->createMock(StringTypeParserInterface::class);

        $typeFactory = $this->createMock(TypeFactoryInterface::class);
        $typeFactory->expects(self::never())
            ->method('getType');

        $extensionMember = new ExtensionMember($typeFactory, $stringTypeParser, [$processorClass]);
        $type = $extensionMember->getType($stringFormType);

        /* @var $type ExtensionClassType */
        self::assertNotNull($type);
        self::assertInstanceOf(ExtensionClassType::class, $type);
        self::assertSame($customType, $type->getCustomType());
        self::assertSame($parameters, $type->getParameters());
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Type handler [a-zA-Z0-9_\\]+ is missing the getType method\./
     */
    public function testCreateThrowsOnInvalidHandlerClass(): void
    {
        $stringTypeParser = $this->createMock(StringTypeParserInterface::class);
        $typeFactory = $this->createMock(TypeFactoryInterface::class);
        $typeFactory->expects(self::never())
            ->method('getType');

        new ExtensionMember($typeFactory, $stringTypeParser, [Car::class]);
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
        $stringTypeParser = $this->createMock(StringTypeParserInterface::class);
        $typeFactory = $this->createMock(TypeFactoryInterface::class);
        $typeFactory->expects(self::never())
            ->method('getType');

        new ExtensionMember($typeFactory, $stringTypeParser, [$processorClass, $processorClass]);
    }

    /**
     *
     */
    public function testGetTypeWithoutRegisteredHandlersReturnsNull(): void
    {
        $typeFactory = $this->createMock(TypeFactoryInterface::class);
        $typeFactory->expects(self::never())
            ->method('getType');
        $stringTypeParser = $this->createMock(StringTypeParserInterface::class);
        $stringFormType = $this->createMock(ContextStringFormTypeInterface::class);

        $customObject = new ExtensionMember($typeFactory, $stringTypeParser);
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
        $stringTypeParser = $this->createMock(StringTypeParserInterface::class);
        $stringFormType = $this->createMock(ContextStringFormTypeInterface::class);
        $stringFormType->method('getStringType')
            ->willReturn('!234');

        $customObject = new ExtensionMember($typeFactory, $stringTypeParser, [get_class($customObject)]);
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
        $stringTypeParser = $this->createMock(StringTypeParserInterface::class);
        $stringFormType = $this->createMock(ContextStringFormTypeInterface::class);
        $stringFormType->method('getStringType')
            ->willReturn(Radio::class);

        $customObject = new ExtensionMember($typeFactory, $stringTypeParser, [get_class($customObject)]);
        $type = $customObject->getType($stringFormType);

        self::assertNull($type);
    }
}

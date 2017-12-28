<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\ExtensionObjectType;
use BetterSerializer\DataBind\MetaData\Type\ExtensionType;
use BetterSerializer\DataBind\MetaData\Type\Parameters\ParametersInterface;
use BetterSerializer\DataBind\MetaData\Type\Parameters\ParserInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\StringFormTypeInterface;
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
        $customObject = ExtensionMockFactory::createTypeExcensionMock($customType);

        $processorClass = get_class($customObject);
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

        $customObject = new ExtensionMember($parser, [$processorClass]);
        $type = $customObject->getType($stringFormType);

        self::assertInstanceOf(ExtensionType::class, $type);
    }

    /**
     * @SuppressWarnings(PHPMD)
     */
    public function testGetTypeWithCustomObject(): void
    {
        $customType = Car::class;
        $customObject = ExtensionMockFactory::createTypeExcensionMock($customType);

        $processorClass = get_class($customObject);
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

        $customObject = new ExtensionMember($parser, [$processorClass]);
        $type = $customObject->getType($stringFormType);

        self::assertInstanceOf(ExtensionObjectType::class, $type);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Type handler [a-zA-Z0-9_\\]+ is missing the getType method\./
     */
    public function testCreateThrowsOnInvalidHandlerClass(): void
    {
        $parser = $this->createMock(ParserInterface::class);

        new ExtensionMember($parser, [Car::class]);
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

        new ExtensionMember($parser, [$processorClass, $processorClass]);
    }

    /**
     *
     */
    public function testGetTypeWithoutRegisteredHandlersReturnsNull(): void
    {
        $parser = $this->createMock(ParserInterface::class);
        $stringFormType = $this->createMock(StringFormTypeInterface::class);

        $customObject = new ExtensionMember($parser);
        $type = $customObject->getType($stringFormType);

        self::assertNull($type);
    }

    /**
     * @SuppressWarnings(PHPMD)
     */
    public function testGetTypeForWrongTypeDefinitionReturnsNull(): void
    {
        $customObject = ExtensionMockFactory::createTypeExcensionMock(Car::class);

        $parser = $this->createMock(ParserInterface::class);
        $stringFormType = $this->createMock(StringFormTypeInterface::class);
        $stringFormType->method('isClass')
            ->willReturn(true);
        $stringFormType->method('getStringType')
            ->willReturn('!234');

        $customObject = new ExtensionMember($parser, [get_class($customObject)]);
        $type = $customObject->getType($stringFormType);

        self::assertNull($type);
    }

    /**
     * @SuppressWarnings(PHPMD)
     */
    public function testGetTypeForUnsupportedCustomObjectReturnsNull(): void
    {
        $customObject = ExtensionMockFactory::createTypeExcensionMock(Car::class);

        $parser = $this->createMock(ParserInterface::class);
        $stringFormType = $this->createMock(StringFormTypeInterface::class);
        $stringFormType->method('isClass')
            ->willReturn(true);
        $stringFormType->method('getStringType')
            ->willReturn(Radio::class);

        $customObject = new ExtensionMember($parser, [get_class($customObject)]);
        $type = $customObject->getType($stringFormType);

        self::assertNull($type);
    }
}

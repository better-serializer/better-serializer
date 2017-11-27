<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\CustomObjectType;
use BetterSerializer\DataBind\MetaData\Type\CustomType;
use BetterSerializer\DataBind\MetaData\Type\Parameters\ParametersInterface;
use BetterSerializer\DataBind\MetaData\Type\Parameters\ParserInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\StringFormTypeInterface;
use BetterSerializer\Dto\Car;
use BetterSerializer\Dto\Radio;
use BetterSerializer\Helper\CustomTypeMockFactory;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 *
 */
class CustomTypeMemberTest extends TestCase
{

    /**
     * @SuppressWarnings(PHPMD)
     */
    public function testGetTypeWithCustomNonObject(): void
    {
        $customType = 'MyType';
        $customObject = CustomTypeMockFactory::createCustomTypeExcensionMock($customType);

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

        $customObject = new CustomTypeMember($parser, [$processorClass]);
        $type = $customObject->getType($stringFormType);

        self::assertInstanceOf(CustomType::class, $type);
    }

    /**
     * @SuppressWarnings(PHPMD)
     */
    public function testGetTypeWithCustomObject(): void
    {
        $customObject = CustomTypeMockFactory::createCustomTypeExcensionMock(Car::class);

        $processorClass = get_class($customObject);
        $stringFormType = $this->createMock(StringFormTypeInterface::class);
        $stringFormType->method('getStringType')
            ->willReturn(Car::class);
        $stringFormType->method('isClass')
            ->willReturn(true);

        $parameters = $this->createMock(ParametersInterface::class);
        $parser = $this->createMock(ParserInterface::class);
        $parser->method('parseParameters')
            ->with($stringFormType)
            ->willReturn($parameters);

        $customObject = new CustomTypeMember($parser, [$processorClass]);
        $type = $customObject->getType($stringFormType);

        self::assertInstanceOf(CustomObjectType::class, $type);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Type handler [a-zA-Z0-9_\\]+ is missing the getType method\./
     */
    public function testCreateThrowsOnInvalidHandlerClass(): void
    {
        $parser = $this->createMock(ParserInterface::class);

        new CustomTypeMember($parser, [Car::class]);
    }

    /**
     * @SuppressWarnings(PHPMD)
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Handler for class [a-zA-Z0-9\\]+ is already registered\./
     */
    public function testAddCustomObjectClassThrowsRuntimeException(): void
    {
        $customObject = CustomTypeMockFactory::createCustomTypeExcensionMock(Car::class);

        $processorClass = get_class($customObject);
        $parser = $this->createMock(ParserInterface::class);

        new CustomTypeMember($parser, [$processorClass, $processorClass]);
    }

    /**
     *
     */
    public function testGetTypeWithoutRegisteredHandlersReturnsNull(): void
    {
        $parser = $this->createMock(ParserInterface::class);
        $stringFormType = $this->createMock(StringFormTypeInterface::class);

        $customObject = new CustomTypeMember($parser);
        $type = $customObject->getType($stringFormType);

        self::assertNull($type);
    }

    /**
     * @SuppressWarnings(PHPMD)
     */
    public function testGetTypeForWrongTypeDefinitionReturnsNull(): void
    {
        $customObject = CustomTypeMockFactory::createCustomTypeExcensionMock(Car::class);

        $parser = $this->createMock(ParserInterface::class);
        $stringFormType = $this->createMock(StringFormTypeInterface::class);
        $stringFormType->method('isClass')
            ->willReturn(true);
        $stringFormType->method('getStringType')
            ->willReturn('!234');

        $customObject = new CustomTypeMember($parser, [get_class($customObject)]);
        $type = $customObject->getType($stringFormType);

        self::assertNull($type);
    }

    /**
     * @SuppressWarnings(PHPMD)
     */
    public function testGetTypeForUnsupportedCustomObjectReturnsNull(): void
    {
        $customObject = CustomTypeMockFactory::createCustomTypeExcensionMock(Car::class);

        $parser = $this->createMock(ParserInterface::class);
        $stringFormType = $this->createMock(StringFormTypeInterface::class);
        $stringFormType->method('isClass')
            ->willReturn(true);
        $stringFormType->method('getStringType')
            ->willReturn(Radio::class);

        $customObject = new CustomTypeMember($parser, [get_class($customObject)]);
        $type = $customObject->getType($stringFormType);

        self::assertNull($type);
    }
}

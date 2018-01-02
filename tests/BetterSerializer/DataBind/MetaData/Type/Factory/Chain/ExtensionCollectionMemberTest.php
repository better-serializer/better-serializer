<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory\Chain;

use BetterSerializer\DataBind\MetaData\Type\ExtensionCollectionType;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parameters\ParametersInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\ParametersParserInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\ContextStringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\StringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\TypeInterface;
use BetterSerializer\Dto\Car;
use BetterSerializer\Helper\ExtensionMockFactory;
use BetterSerializer\Reflection\ReflectionClassInterface;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 *
 */
class ExtensionCollectionMemberTest extends TestCase
{

    /**
     * @SuppressWarnings(PHPMD)
     */
    public function testGetTypeWithCustomCollection(): void
    {
        $customType = Collection::class;
        $customCollectionMember = ExtensionMockFactory::createTypeExcensionMock($customType);

        $processorClass = get_class($customCollectionMember);
        $colValStringFormType = $stringFormType = $this->createMock(ContextStringFormTypeInterface::class);

        $stringFormType = $this->createMock(ContextStringFormTypeInterface::class);
        $stringFormType->expects(self::exactly(2))
            ->method('getStringType')
            ->willReturn($customType);
        $stringFormType->expects(self::exactly(2))
            ->method('getCollectionValueType')
            ->willReturn($colValStringFormType);
        $stringFormType->expects(self::once())
            ->method('getParameters')
            ->willReturn(null);

        $nestedType = $this->createMock(TypeInterface::class);

        $typeFactory = $this->createMock(TypeFactoryInterface::class);
        $typeFactory->expects(self::once())
            ->method('getType')
            ->willReturn($nestedType);

        $customCollectionMember = new ExtensionCollectionMember($typeFactory, [$processorClass]);
        $type = $customCollectionMember->getType($stringFormType);

        self::assertInstanceOf(ExtensionCollectionType::class, $type);
    }

    /**
     * @SuppressWarnings(PHPMD)
     */
    public function testGetTypeReturnsNullWhenTypeNotSupported(): void
    {
        $customType = 'MyCollectionTypeUnsupported';
        $customType2 = 'MyCollectionType';
        $customCollectionMember = ExtensionMockFactory::createTypeExcensionMock($customType2);

        $processorClass = get_class($customCollectionMember);
        $colValStringFormType = $stringFormType = $this->createMock(ContextStringFormTypeInterface::class);
        $stringFormType = $this->createMock(ContextStringFormTypeInterface::class);
        $stringFormType->expects(self::once())
            ->method('getStringType')
            ->willReturn($customType);
        $stringFormType->expects(self::once())
            ->method('getCollectionValueType')
            ->willReturn($colValStringFormType);

        $typeFactory = $this->createMock(TypeFactoryInterface::class);

        $customCollectionMember = new ExtensionCollectionMember($typeFactory, [$processorClass]);
        $type = $customCollectionMember->getType($stringFormType);

        self::assertNull($type);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Type handler [a-zA-Z0-9_\\]+ is missing the getType method\./
     */
    public function testCreateThrowsOnInvalidHandlerClass(): void
    {
        $typeFactory = $this->createMock(TypeFactoryInterface::class);

        new ExtensionCollectionMember($typeFactory, [Car::class]);
    }

    /**
     * @SuppressWarnings(PHPMD)
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Handler for class [a-zA-Z0-9\\]+ is already registered\./
     */
    public function testAddCustomObjectClassThrowsRuntimeException(): void
    {
        $customObject = ExtensionMockFactory::createTypeExcensionMock(Car::class);

        $typeFactory = $this->createMock(TypeFactoryInterface::class);
        $processorClass = get_class($customObject);

        new ExtensionCollectionMember($typeFactory, [$processorClass, $processorClass]);
    }

    /**
     *
     */
    public function testGetTypeWithoutRegisteredHandlersReturnsNull(): void
    {
        $typeFactory = $this->createMock(TypeFactoryInterface::class);
        $stringFormType = $this->createMock(ContextStringFormTypeInterface::class);

        $customColMember = new ExtensionCollectionMember($typeFactory);
        $type = $customColMember->getType($stringFormType);

        self::assertNull($type);
    }
}

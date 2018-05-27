<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\PropertyReader\TypeResolver;

use BetterSerializer\DataBind\MetaData\Reader\PropertyReader\Context\PropertyContextInterface;
use BetterSerializer\DataBind\MetaData\Type\Factory\TypeFactoryInterface;
use BetterSerializer\DataBind\MetaData\Type\StringFormType\ContextStringFormTypeInterface;
use BetterSerializer\DataBind\MetaData\Type\StringType;
use BetterSerializer\Reflection\ReflectionClassInterface;
use BetterSerializer\Reflection\ReflectionPropertyInterface;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class TypeResolverChainTest extends TestCase
{

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Type readers missing.
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     * @throws \ReflectionException
     */
    public function testConstructionWithEmptyTypeReadersThrowsException(): void
    {
        $typeFactoryStub = $this->createMock(TypeFactoryInterface::class);

        new TypeResolverChain($typeFactoryStub, []);
    }

    /**
     * @throws
     */
    public function testResolveType(): void
    {
        $stringTypeStub = new StringType();
        $stringFormTypeStub = $this->createMock(ContextStringFormTypeInterface::class);
        $propertyContext = $this->createMock(PropertyContextInterface::class);

        $typeReaderStub = $this->createMock(TypeResolverInterface::class);
        $typeReaderStub->expects(self::once())
            ->method('resolveType')
            ->with($propertyContext)
            ->willReturn($stringFormTypeStub);

        $typeFactoryStub = $this->createMock(TypeFactoryInterface::class);
        $typeFactoryStub->expects(self::once())
            ->method('getType')
            ->with($stringFormTypeStub)
            ->willReturn($stringTypeStub);

        $chain = new TypeResolverChain($typeFactoryStub, [$typeReaderStub]);
        $type = $chain->resolveType($propertyContext);

        self::assertEquals($stringTypeStub, $type);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Type declaration missing in class: [a-zA-Z0-9\\]+, property: [a-zA-Z0-9]+/
     * @throws RuntimeException
     * @throws \InvalidArgumentException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \ReflectionException
     */
    public function testResolveTypeThrowsException(): void
    {
        $typeFactoryStub = $this->createMock(TypeFactoryInterface::class);
        $typeFactoryStub->expects(self::exactly(0))
            ->method('getType');

        $typeReaderStub = $this->createMock(TypeResolverInterface::class);
        $typeReaderStub->expects(self::once())
            ->method('resolveType')
            ->willReturn(null);

        $reflDeclClassStub = $this->createMock(ReflectionClassInterface::class);
        $reflDeclClassStub->expects(self::once())
            ->method('getName')
            ->willReturn(StringType::class);

        $reflPropertyStub1 = $this->createMock(ReflectionPropertyInterface::class);
        $reflPropertyStub1->method('getName')
            ->willReturn('property1');
        $reflPropertyStub1->expects(self::once())
            ->method('getDeclaringClass')
            ->willReturn($reflDeclClassStub);

        $propertyContext = $this->createMock(PropertyContextInterface::class);
        $propertyContext->method('getReflectionProperty')
            ->willReturn($reflPropertyStub1);

        $chain = new TypeResolverChain($typeFactoryStub, [$typeReaderStub]);
        $chain->resolveType($propertyContext);
    }
}

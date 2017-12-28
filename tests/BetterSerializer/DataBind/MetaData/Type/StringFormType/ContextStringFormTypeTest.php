<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType;

use BetterSerializer\Dto\Car;
use BetterSerializer\Reflection\ReflectionClassInterface;
use BetterSerializer\Reflection\UseStatement\UseStatementInterface;
use BetterSerializer\Reflection\UseStatement\UseStatementsInterface;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;
use DateTimeImmutable;

/**
 * Class StringFormTypeTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type\StringFormType
 */
class ContextStringFormTypeTest extends TestCase
{
    /**
     *
     */
    public function testEverythingForInternalType(): void
    {
        $type = 'string';
        $namespace = 'test';
        $reflectionClass = $this->createMock(ReflectionClassInterface::class);
        $reflectionClass->method('getNamespaceName')
            ->willReturn($namespace);

        $stringFormType = new ContextStringFormType($type, $reflectionClass);

        self::assertSame($type, $stringFormType->getStringType());
        self::assertSame($namespace, $stringFormType->getNamespace());
        self::assertFalse($stringFormType->isClass());
        self::assertFalse($stringFormType->isInterface());
        self::assertFalse($stringFormType->isClassOrInterface());
        self::assertNull($stringFormType->getCollectionKeyType());
        self::assertNull($stringFormType->getCollectionValueType());
        self::assertSame($reflectionClass, $stringFormType->getReflectionClass());
    }

    /**
     *
     */
    public function testEverythingForArrayType(): void
    {
        $type = 'string[]';
        $namespace = 'test';
        $reflectionClass = $this->createMock(ReflectionClassInterface::class);
        $reflectionClass->method('getNamespaceName')
            ->willReturn($namespace);

        $stringFormType = new ContextStringFormType($type, $reflectionClass);

        self::assertSame($type, $stringFormType->getStringType());
        self::assertSame($namespace, $stringFormType->getNamespace());
        self::assertFalse($stringFormType->isClass());
        self::assertFalse($stringFormType->isInterface());
        self::assertFalse($stringFormType->isClassOrInterface());
        self::assertNull($stringFormType->getCollectionKeyType());
        self::assertNull($stringFormType->getCollectionValueType());
        self::assertSame($reflectionClass, $stringFormType->getReflectionClass());
    }

    /**
     *
     */
    public function testEverythingForUnexpectedType(): void
    {
        $type = '\Car';
        $namespace = 'Test';
        $useStatements = $this->createMock(UseStatementsInterface::class);
        $useStatements->method('hasByIdentifier')
            ->with('Car')
            ->willReturn(false);
        $useStatements->method('hasByAlias')
            ->with('Car')
            ->willReturn(false);

        $reflectionClass = $this->createMock(ReflectionClassInterface::class);
        $reflectionClass->method('getNamespaceName')
            ->willReturn($namespace);
        $reflectionClass->method('getUseStatements')
            ->willReturn($useStatements);

        $stringFormType = new ContextStringFormType($type, $reflectionClass);

        self::assertSame($type, $stringFormType->getStringType());
        self::assertSame($namespace, $stringFormType->getNamespace());
        self::assertFalse($stringFormType->isClass());
        self::assertFalse($stringFormType->isInterface());
        self::assertFalse($stringFormType->isClassOrInterface());
        self::assertNull($stringFormType->getCollectionKeyType());
        self::assertNull($stringFormType->getCollectionValueType());
    }


    /**
     * @param string $type
     * @param string $namespace
     * @param string $useStatementId
     * @param UseStatementInterface|null $useStatement
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     * @dataProvider dataProviderForTestEverythingForClass
     */
    public function testEverythingForClassWithUseIdentifier(
        string $type,
        string $namespace,
        string $useStatementId,
        UseStatementInterface $useStatement = null
    ): void {
        $useStatements = $this->createMock(UseStatementsInterface::class);
        $useStatements->method('hasByIdentifier')
            ->with($useStatementId)
            ->willReturn($useStatement !== null);

        if ($useStatement) {
            $useStatements->method('getByIdentifier')
                ->with($useStatementId)
                ->willReturn($useStatement);
        }

        $reflectionClass = $this->createMock(ReflectionClassInterface::class);
        $reflectionClass->method('getNamespaceName')
            ->willReturn($namespace);
        $reflectionClass->method('getUseStatements')
            ->willReturn($useStatements);

        $stringFormType = new ContextStringFormType($type, $reflectionClass);

        self::assertTrue($stringFormType->isClass());
        self::assertFalse($stringFormType->isInterface());
        self::assertTrue($stringFormType->isClassOrInterface());
        self::assertNull($stringFormType->getCollectionKeyType());
        self::assertNull($stringFormType->getCollectionValueType());
    }

    /**
     * @return array
     * @throws \PHPUnit\Framework\Exception
     */
    public function dataProviderForTestEverythingForClass(): array
    {
        return [
            $this->getDataWithExistingIdentifier(),
            $this->getDataWithNonExistingIdentifier(),
        ];
    }

    /**
     * @return array
     * @throws \PHPUnit\Framework\Exception
     */
    private function getDataWithExistingIdentifier(): array
    {
        $useStatement = $this->createMock(UseStatementInterface::class);
        $useStatement->method('getFqdn')
            ->willReturn(Car::class);

        return ['\\Car', 'Test', 'Car', $useStatement];
    }

    /**
     * @return array
     */
    private function getDataWithNonExistingIdentifier(): array
    {
        return ['\\' . DateTimeImmutable::class, 'BetterSerializer\Dto', 'DateTimeImmutable', null];
    }

    /**
     * @param string $type
     * @param string $namespace
     * @param string $useStatementId
     * @param UseStatementInterface|null $useStatement
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     * @dataProvider dataProviderForTestEverythingForClass
     */
    public function testEverythingForClassWithUseAlias(
        string $type,
        string $namespace,
        string $useStatementId,
        UseStatementInterface $useStatement = null
    ): void {
        $useStatements = $this->createMock(UseStatementsInterface::class);
        $useStatements->method('hasByAlias')
            ->with($useStatementId)
            ->willReturn($useStatement !== null);

        if ($useStatement) {
            $useStatements->method('getByAlias')
                ->with($useStatementId)
                ->willReturn($useStatement);
        }

        $reflectionClass = $this->createMock(ReflectionClassInterface::class);
        $reflectionClass->method('getNamespaceName')
            ->willReturn($namespace);
        $reflectionClass->method('getUseStatements')
            ->willReturn($useStatements);

        $stringFormType = new ContextStringFormType($type, $reflectionClass);

        self::assertTrue($stringFormType->isClass());
        self::assertFalse($stringFormType->isInterface());
        self::assertTrue($stringFormType->isClassOrInterface());
        self::assertNull($stringFormType->getCollectionKeyType());
        self::assertNull($stringFormType->getCollectionValueType());
    }

    public function testEverythingForClassWithoutUses(): void
    {
        $type = '\\Radio';
        $useStatementId = 'Radio';
        $namespace = 'BetterSerializer\Dto';
        $useStatements = $this->createMock(UseStatementsInterface::class);

        $useStatements->method('hasByIdentifier')
            ->with($useStatementId)
            ->willReturn(false);

        $useStatements->method('hasByAlias')
            ->with($useStatementId)
            ->willReturn(false);

        $reflectionClass = $this->createMock(ReflectionClassInterface::class);
        $reflectionClass->method('getNamespaceName')
            ->willReturn($namespace);
        $reflectionClass->method('getUseStatements')
            ->willReturn($useStatements);

        $stringFormType = new ContextStringFormType($type, $reflectionClass);

        self::assertTrue($stringFormType->isClass());
        self::assertFalse($stringFormType->isInterface());
        self::assertTrue($stringFormType->isClassOrInterface());
        self::assertNull($stringFormType->getCollectionKeyType());
        self::assertNull($stringFormType->getCollectionValueType());
    }

    public function testEverythingForInterfaceWithoutUses(): void
    {
        $type = '\\RadioInterface';
        $useStatementId = 'RadioInterface';
        $namespace = 'BetterSerializer\Dto';
        $useStatements = $this->createMock(UseStatementsInterface::class);

        $useStatements->method('hasByIdentifier')
            ->with($useStatementId)
            ->willReturn(false);

        $useStatements->method('hasByAlias')
            ->with($useStatementId)
            ->willReturn(false);

        $reflectionClass = $this->createMock(ReflectionClassInterface::class);
        $reflectionClass->method('getNamespaceName')
            ->willReturn($namespace);
        $reflectionClass->method('getUseStatements')
            ->willReturn($useStatements);

        $stringFormType = new ContextStringFormType($type, $reflectionClass);

        self::assertFalse($stringFormType->isClass());
        self::assertTrue($stringFormType->isInterface());
        self::assertTrue($stringFormType->isClassOrInterface());
        self::assertNull($stringFormType->getCollectionKeyType());
        self::assertNull($stringFormType->getCollectionValueType());
    }

    public function testEverythingForClassWithCollectionValueWithoutUses(): void
    {
        $type = '\\Collection<string>';
        $useStatementId1 = 'Collection';
        $useStatementId2 = 'string';
        $namespace = 'Doctrine\\Common\\Collections';
        $useStatements = $this->createMock(UseStatementsInterface::class);

        $useStatements->expects(self::exactly(2))
            ->method('hasByIdentifier')
            ->withConsecutive([$useStatementId1], [$useStatementId2])
            ->willReturn(false);

        $useStatements->expects(self::exactly(2))
            ->method('hasByAlias')
            ->withConsecutive([$useStatementId1], [$useStatementId2])
            ->willReturn(false);

        $reflectionClass = $this->createMock(ReflectionClassInterface::class);
        $reflectionClass->method('getNamespaceName')
            ->willReturn($namespace);
        $reflectionClass->method('getUseStatements')
            ->willReturn($useStatements);

        $stringFormType = new ContextStringFormType($type, $reflectionClass);

        self::assertFalse($stringFormType->isClass());
        self::assertTrue($stringFormType->isInterface());
        self::assertTrue($stringFormType->isClassOrInterface());
        self::assertNull($stringFormType->getCollectionKeyType());
        $colValueType = $stringFormType->getCollectionValueType();
        self::assertNotNull($colValueType);
        self::assertInstanceOf(ContextStringFormType::class, $colValueType);
        self::assertSame(Collection::class, $stringFormType->getStringType());

        self::assertFalse($colValueType->isClass());
        self::assertFalse($colValueType->isInterface());
        self::assertFalse($colValueType->isClassOrInterface());
        self::assertNull($colValueType->getCollectionKeyType());
        self::assertNull($colValueType->getCollectionValueType());
        self::assertSame($useStatementId2, $colValueType->getStringType());
    }

    public function testEverythingForClassWithCollectionKeyAndValueWithoutUses(): void
    {
        $type = '\\Collection<string, int>';
        $useStatementId1 = 'Collection';
        $useStatementId2 = 'string';
        $useStatementId3 = 'int';
        $namespace = 'Doctrine\\Common\\Collections';
        $useStatements = $this->createMock(UseStatementsInterface::class);

        $useStatements->expects(self::exactly(3))
            ->method('hasByIdentifier')
            ->withConsecutive([$useStatementId1], [$useStatementId2], [$useStatementId3])
            ->willReturn(false);

        $useStatements->expects(self::exactly(3))
            ->method('hasByAlias')
            ->withConsecutive([$useStatementId1], [$useStatementId2], [$useStatementId3])
            ->willReturn(false);

        $reflectionClass = $this->createMock(ReflectionClassInterface::class);
        $reflectionClass->method('getNamespaceName')
            ->willReturn($namespace);
        $reflectionClass->method('getUseStatements')
            ->willReturn($useStatements);

        $stringFormType = new ContextStringFormType($type, $reflectionClass);

        self::assertFalse($stringFormType->isClass());
        self::assertTrue($stringFormType->isInterface());
        self::assertTrue($stringFormType->isClassOrInterface());
        self::assertSame(Collection::class, $stringFormType->getStringType());
        $colKeyType = $stringFormType->getCollectionKeyType();
        self::assertNotNull($colKeyType);
        self::assertInstanceOf(ContextStringFormType::class, $colKeyType);
        $colValueType = $stringFormType->getCollectionValueType();
        self::assertNotNull($colValueType);
        self::assertInstanceOf(ContextStringFormType::class, $colValueType);

        self::assertFalse($colKeyType->isClass());
        self::assertFalse($colKeyType->isInterface());
        self::assertFalse($colKeyType->isClassOrInterface());
        self::assertNull($colKeyType->getCollectionKeyType());
        self::assertNull($colKeyType->getCollectionValueType());
        self::assertSame($useStatementId2, $colKeyType->getStringType());

        self::assertFalse($colValueType->isClass());
        self::assertFalse($colValueType->isInterface());
        self::assertFalse($colValueType->isClassOrInterface());
        self::assertNull($colValueType->getCollectionKeyType());
        self::assertNull($colValueType->getCollectionValueType());
        self::assertSame($useStatementId3, $colValueType->getStringType());
    }
}

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
    }


    /**
     * @param string $type
     * @param string $namespace
     * @param string $useStatementId
     * @param UseStatementInterface|null $useStatement
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
    }

    /**
     * @return array
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
    }

    public function testEverythingForClassWithputUses(): void
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
    }
}

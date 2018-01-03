<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\HigherType;

use BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\ContextInterface;
use BetterSerializer\Dto\Car;
use BetterSerializer\Reflection\UseStatement\UseStatementInterface;
use BetterSerializer\Reflection\UseStatement\UseStatementsInterface;
use PHPUnit\Framework\TestCase;
use DateTimeImmutable;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class UseStmtGuesserTest extends TestCase
{

    /**
     * @param string $type
     * @param string $namespace
     * @param string $expected
     * @throws \LogicException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @dataProvider guessForTypeMissingInUseStatementsDataProvider
     */
    public function testGuessForTypeMissingInUseStatements(string $type, string $namespace, string $expected): void
    {
        $nsFragmentsParser = $this->createMock(NamespaceFragmentsParserInterface::class);

        $useStatements = $this->createMock(UseStatementsInterface::class);
        $useStatements->expects(self::once())
            ->method('hasByIdentifier')
            ->willReturn(false);
        $useStatements->expects(self::once())
            ->method('hasByAlias')
            ->willReturn(false);

        $context = $this->createMock(ContextInterface::class);
        $context->expects(self::once())
            ->method('getNamespace')
            ->willReturn($namespace);
        $context->expects(self::once())
            ->method('getUseStatements')
            ->willReturn($useStatements);

        $guesser = new UseStmtGuesser($nsFragmentsParser);
        $potentialHigherType = $guesser->guess($type, $context);

        self::assertSame($expected, $potentialHigherType);
    }

    /**
     * @return array
     */
    public function guessForTypeMissingInUseStatementsDataProvider(): array
    {
        return [
            ['string', 'test', 'test\\string'],
            ['\\Car', 'Test', 'Test\\Car'],
        ];
    }

    /**
     * @param string $type
     * @param string $namespace
     * @param string $useStatementId
     * @param NamespaceFragmentsInterface $nsFragments
     * @param UseStatementInterface|null $useStatement
     * @param string $expected
     * @throws \PHPUnit\Framework\AssertionFailedError
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \LogicException
     * @dataProvider guessForClassPresentInUseClausesDataProvider
     */
    public function testGuessForClassWithUseIdentifier(
        string $type,
        string $namespace,
        string $useStatementId,
        NamespaceFragmentsInterface $nsFragments,
        ?UseStatementInterface $useStatement,
        string $expected
    ): void {
        $nsFragmentsParser = $this->createMock(NamespaceFragmentsParserInterface::class);
        $nsFragmentsParser->expects(self::once())
            ->method('parse')
            ->with(ltrim($type, '\\'))
            ->willReturn($nsFragments);

        $useStatements = $this->createMock(UseStatementsInterface::class);
        $useStatements->method('hasByIdentifier')
            ->with($useStatementId)
            ->willReturn($useStatement !== null);

        if ($useStatement) {
            $useStatements->method('getByIdentifier')
                ->with($useStatementId)
                ->willReturn($useStatement);
        }

        $context = $this->createMock(ContextInterface::class);
        $context->method('getNamespace')
            ->willReturn($namespace);
        $context->method('getUseStatements')
            ->willReturn($useStatements);

        $guesser = new UseStmtGuesser($nsFragmentsParser);
        $potentialHigherType = $guesser->guess($type, $context);

        self::assertSame($expected, $potentialHigherType);
    }

    /**
     * @return array
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     */
    public function guessForClassPresentInUseClausesDataProvider(): array
    {
        return [
            $this->getDataWithExistingIdentifier(),
            $this->getDataWithNonExistingIdentifier(),
        ];
    }

    /**
     * @return array
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     */
    private function getDataWithExistingIdentifier(): array
    {
        $nsFragments = $this->createMock(NamespaceFragmentsInterface::class);
        $nsFragments->expects(self::once())
            ->method('getFirst')
            ->willReturn('Car');
        $nsFragments->expects(self::once())
            ->method('getWithoutFirst')
            ->willReturn('');

        $useStatement = $this->createMock(UseStatementInterface::class);
        $useStatement->method('getFqdn')
            ->willReturn(Car::class);

        return ['\\Car', 'Test', 'Car', $nsFragments, $useStatement, Car::class];
    }

    /**
     * @return array
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     */
    private function getDataWithNonExistingIdentifier(): array
    {
        $nsFragments = $this->createMock(NamespaceFragmentsInterface::class);
        $nsFragments->expects(self::once())
            ->method('getFirst')
            ->willReturn(DateTimeImmutable::class);

        return [
            '\\' . DateTimeImmutable::class,
            'BetterSerializer\Dto',
            'DateTimeImmutable',
            $nsFragments,
            null,
            'BetterSerializer\Dto\DateTimeImmutable'
        ];
    }

    /**
     * @param string $type
     * @param string $namespace
     * @param string $useStatementId
     * @param NamespaceFragmentsInterface $nsFragments
     * @param UseStatementInterface|null $useStatement
     * @param string $expected
     * @throws \LogicException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @dataProvider guessForClassPresentInUseClausesDataProvider
     */
    public function testEverythingForClassWithUseAlias(
        string $type,
        string $namespace,
        string $useStatementId,
        NamespaceFragmentsInterface $nsFragments,
        ?UseStatementInterface $useStatement,
        string $expected
    ): void {
        $nsFragmentsParser = $this->createMock(NamespaceFragmentsParserInterface::class);
        $nsFragmentsParser->expects(self::once())
            ->method('parse')
            ->with(ltrim($type, '\\'))
            ->willReturn($nsFragments);

        $useStatements = $this->createMock(UseStatementsInterface::class);
        $useStatements->method('hasByAlias')
            ->with($useStatementId)
            ->willReturn($useStatement !== null);

        if ($useStatement) {
            $useStatements->method('getByAlias')
                ->with($useStatementId)
                ->willReturn($useStatement);
        }

        $context = $this->createMock(ContextInterface::class);
        $context->method('getNamespace')
            ->willReturn($namespace);
        $context->method('getUseStatements')
            ->willReturn($useStatements);

        $guesser = new UseStmtGuesser($nsFragmentsParser);
        $potentialHigherType = $guesser->guess($type, $context);

        self::assertSame($expected, $potentialHigherType);
    }
}

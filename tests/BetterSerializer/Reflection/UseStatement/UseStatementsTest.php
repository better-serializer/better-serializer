<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Reflection\UseStatement;

use PHPUnit\Framework\TestCase;
use LogicException;

/**
 * Class UseStatementsTest
 * @author mfris
 * @package BetterSerializer\Reflection\UseStatement
 */
class UseStatementsTest extends TestCase
{

    /**
     *
     */
    public function testEverything(): void
    {
        $useStatement1 = $this->createMock(UseStatementInterface::class);
        $useStatement1->method('getIdentifier')
            ->willReturn('id1');
        $useStatement1->method('getAlias')
            ->willReturn('');

        $useStatement2 = $this->createMock(UseStatementInterface::class);
        $useStatement2->method('getIdentifier')
            ->willReturn('id2');
        $useStatement2->method('getAlias')
            ->willReturn('alias2');

        $useStatements = new UseStatements([$useStatement1, $useStatement2]);

        $useStatementById1 = $useStatements->getByIdentifier('id1');
        $useStatementById2 = $useStatements->getByIdentifier('id2');
        $useStatementByAlias2 = $useStatements->getByAlias('alias2');

        self::assertInstanceOf(UseStatementInterface::class, $useStatementById1);
        self::assertInstanceOf(UseStatementInterface::class, $useStatementById2);
        self::assertInstanceOf(UseStatementInterface::class, $useStatementByAlias2);
        self::assertTrue($useStatements->hasByIdentifier('id1'));
        self::assertFalse($useStatements->hasByIdentifier('id3'));
        self::assertTrue($useStatements->hasByAlias('alias2'));
        self::assertFalse($useStatements->hasByAlias('alias1'));

        self::assertSame($useStatementById2, $useStatementByAlias2);
        self::assertNotSame($useStatementById1, $useStatementById2);
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage Identifier cannot be empty.
     */
    public function testGetByIdentifierThrows(): void
    {
        $useStatements = new UseStatements([]);
        $useStatements->getByIdentifier('');
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage Alias cannot be empty.
     */
    public function testGetByAliasThrows(): void
    {
        $useStatements = new UseStatements([]);
        $useStatements->getByAlias('');
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage Identifier cannot be empty.
     */
    public function testHasByIdentifierThrows(): void
    {
        $useStatements = new UseStatements([]);
        $useStatements->hasByIdentifier('');
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage Alias cannot be empty.
     */
    public function testHasByAliasThrows(): void
    {
        $useStatements = new UseStatements([]);
        $useStatements->hasByAlias('');
    }
}

<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Reflection\UseStatement;

use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;

/**
 * Class UseStatementTest
 * @author mfris
 * @package BetterSerializer\Reflection
 */
class UseStatementTest extends TestCase
{

    /**
     *
     */
    public function testEverything(): void
    {
        $fqdn = Car::class;
        $alias = 'TestAlias';

        $useStmt = new UseStatement($fqdn, $alias);

        self::assertSame($fqdn, $useStmt->getFqdn());
        self::assertSame($alias, $useStmt->getAlias());
        self::assertSame('BetterSerializer\\Dto', $useStmt->getNamespace());
        self::assertSame('Car', $useStmt->getIdentifier());

        $useStmt2 = new UseStatement($fqdn, $alias);
        self::assertSame('Car', $useStmt2->getIdentifier());
    }
}

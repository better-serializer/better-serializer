<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver;

use BetterSerializer\Reflection\UseStatement\UseStatementsInterface;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class ContextTest extends TestCase
{

    /**
     *
     */
    public function testEverything(): void
    {
        $namespace = 'test';
        $useStatements = $this->createMock(UseStatementsInterface::class);

        $context = new Context($namespace, $useStatements);

        self::assertSame($namespace, $context->getNamespace());
        self::assertSame($useStatements, $context->getUseStatements());

        $context = new Context();

        self::assertSame('', $context->getNamespace());
        self::assertInstanceOf(UseStatementsInterface::class, $context->getUseStatements());
    }
}

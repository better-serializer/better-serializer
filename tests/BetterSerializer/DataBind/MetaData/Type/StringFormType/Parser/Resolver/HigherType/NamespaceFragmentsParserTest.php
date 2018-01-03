<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\HigherType;

use BetterSerializer\Dto\CarInterface;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class NamespaceFragmentsParserTest extends TestCase
{

    /**
     * @param string $namespace
     * @param string $first
     * @param string $last
     * @param string $withoutFirst
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @dataProvider nsDataProvider
     */
    public function testEverything(string $namespace, string $first, string $last, string $withoutFirst): void
    {
        $parser = new NamespaceFragmentsParser();
        $nsFragments = $parser->parse($namespace);

        self::assertNotNull($nsFragments);
        self::assertInstanceOf(NamespaceFragmentsInterface::class, $nsFragments);
        self::assertSame($first, $nsFragments->getFirst());
        self::assertSame($last, $nsFragments->getLast());
        self::assertSame($withoutFirst, $nsFragments->getWithoutFirst());
    }

    /**
     * @return array
     */
    public function nsDataProvider(): array
    {
        return [
            [
                CarInterface::class,
                'BetterSerializer',
                'CarInterface',
                'Dto\CarInterface',
            ],
            [
                'Test',
                'Test',
                'Test',
                '',
            ],
        ];
    }
}

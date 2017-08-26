<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType;

use BetterSerializer\Dto\CarInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class NamespaceFragmentsTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type\StringFormType
 */
class NamespaceFragmentsTest extends TestCase
{

    /**
     * @param string $namespace
     * @param string $first
     * @param string $last
     * @param string $withoutFirst
     * @dataProvider nsDataProvider
     */
    public function testEverything(string $namespace, string $first, string $last, string $withoutFirst): void
    {
        $nsFragments = new NamespaceFragments($namespace);

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

<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType\Parser\Resolver\HigherType;

use PHPUnit\Framework\TestCase;

/**
 *
 */
class NamespaceFragmentsTest extends TestCase
{

    /**
     *
     */
    public function testEverything(): void
    {
        $first = 'BetterSerializer';
        $last = 'CarInterface';
        $withoutFirst = 'Dto\CarInterface';

        $nsFragments = new NamespaceFragments($first, $last, $withoutFirst);

        self::assertSame($first, $nsFragments->getFirst());
        self::assertSame($last, $nsFragments->getLast());
        self::assertSame($withoutFirst, $nsFragments->getWithoutFirst());
    }
}

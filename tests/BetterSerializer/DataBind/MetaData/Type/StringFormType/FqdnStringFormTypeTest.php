<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType;

use BetterSerializer\Dto\Car;
use PHPUnit\Framework\TestCase;

/**
 * Class FqdnStringFormTypeTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type\StringFormType
 */
class FqdnStringFormTypeTest extends TestCase
{

    /**
     * @param string $fqdn
     * @param string $namespace
     * @param bool $isClass
     * @dataProvider dataProvider
     */
    public function testEverything(string $fqdn, string $namespace, bool $isClass): void
    {
        $strinFormType = new FqdnStringFormType($fqdn);

        self::assertSame($fqdn, $strinFormType->getStringType());
        self::assertSame($namespace, $strinFormType->getNamespace());
        self::assertSame($isClass, $strinFormType->isClass());
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [Car::class, 'BetterSerializer\Dto', true],
            ['BetterSerializer\Dto\Car[]', '', false],
            ['array<BetterSerializer\Dto\Car>', '', false],
            ['int', '', false],
            ['int[]', '', false],
        ];
    }
}

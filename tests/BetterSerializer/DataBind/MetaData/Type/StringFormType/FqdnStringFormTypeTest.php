<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\StringFormType;

use BetterSerializer\Dto\Car;
use BetterSerializer\Dto\CarInterface;
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
     * @param bool $isInterface
     * @param bool $isClassOrInterface
     * @dataProvider dataProvider
     */
    public function testEverything(
        string $fqdn,
        string $namespace,
        bool $isClass,
        bool $isInterface,
        bool $isClassOrInterface
    ): void {
        $strinFormType = new FqdnStringFormType($fqdn);

        self::assertSame($fqdn, $strinFormType->getStringType());
        self::assertSame($namespace, $strinFormType->getNamespace());
        self::assertSame($isClass, $strinFormType->isClass());
        self::assertSame($isInterface, $strinFormType->isInterface());
        self::assertSame($isClassOrInterface, $strinFormType->isClassOrInterface());
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [Car::class, 'BetterSerializer\Dto', true, false, true],
            ['BetterSerializer\Dto\Car[]', '', false, false, false],
            ['array<BetterSerializer\Dto\Car>', '', false, false, false],
            ['int', '', false, false, false],
            ['int[]', '', false, false, false],
            [CarInterface::class, 'BetterSerializer\Dto', false, true, true],
        ];
    }
}

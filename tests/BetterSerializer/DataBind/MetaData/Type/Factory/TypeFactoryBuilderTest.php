<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Type\Factory;

use PHPUnit\Framework\TestCase;

/**
 * Class TypeFactoryBuilderTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Type\Factory
 */
class TypeFactoryBuilderTest extends TestCase
{

    /**
     *
     */
    public function testBuild(): void
    {
        $builder = new TypeFactoryBuilder();
        $factory = $builder->build();

        self::assertInstanceOf(TypeFactoryInterface::class, $factory);
    }
}

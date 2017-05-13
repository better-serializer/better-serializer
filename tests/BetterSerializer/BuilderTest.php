<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer;

use PHPUnit\Framework\TestCase;

/**
 * Class BuilderTest
 * @author mfris
 * @package BetterSerializer
 */
class BuilderTest extends TestCase
{

    /**
     *
     */
    public function testCreateSerializer(): void
    {
        $builder = new Builder();
        $serializer = $builder->createSerializer();

        self::assertInstanceOf(Serializer::class, $serializer);
    }
}

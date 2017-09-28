<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer;

use PHPUnit\Framework\TestCase;
use RuntimeException;

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
    public function testBuild(): void
    {
        $builder = new Builder();
        $builder->setCacheDir(__DIR__);
        $builder->enableApcuCache();
        $serializer = $builder->createSerializer();

        self::assertInstanceOf(Serializer::class, $serializer);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Invalid directory: [a-zA-Z0-9\/_\-]+/
     */
    public function testSetCacheDirThrows(): void
    {
        $builder = new Builder();
        $builder->setCacheDir(__DIR__ . '/xxxzzz');
    }
}

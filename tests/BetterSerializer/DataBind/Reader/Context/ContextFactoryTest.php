<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Context;

use BetterSerializer\Common\SerializationType;
use BetterSerializer\DataBind\Reader\Context\Json\Context as JsonContext;
use BetterSerializer\DataBind\Reader\Context\PhpArray\Context as PhpArrayContext;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class ContextFactoryTest extends TestCase
{

    /**
     * @dataProvider deserializationTypeProvider
     * @param mixed $serialized
     * @param SerializationType $serializationType
     * @param string $contextClass
     * @throws RuntimeException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testCreateContext(
        $serialized,
        SerializationType $serializationType,
        string $contextClass
    ): void {
        $factory = new ContextFactory();
        $context = $factory->createContext($serialized, $serializationType);

        self::assertInstanceOf($contextClass, $context);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Invalid serialization type: [a-zA-Z0-9_]+/
     */
    public function testCreateContextWithUnsupportedType(): void
    {
        $factory = new ContextFactory();
        $factory->createContext('xxx', SerializationType::NONE());
    }

    /**
     * @return array
     */
    public function deserializationTypeProvider(): array
    {
        return [
            ['{"a":"a"}', SerializationType::JSON(), JsonContext::class],
            [['a' => 'a'], SerializationType::PHP_ARRAY(), PhpArrayContext::class]
        ];
    }
}

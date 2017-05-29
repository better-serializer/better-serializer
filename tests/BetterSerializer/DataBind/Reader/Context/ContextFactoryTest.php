<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Reader\Context;

use BetterSerializer\Common\SerializationType;
use BetterSerializer\DataBind\Reader\Context\Json\Context as JsonContext;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * Class ContextFactoryTest
 * @author mfris
 * @package BetterSerializer\DataBind\Reader\Context
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class ContextFactoryTest extends TestCase
{

    /**
     * @dataProvider deserializationTypeProvider
     * @param string $serialized
     * @param SerializationType $serializationType
     * @param string $contextClass
     */
    public function testCreateContext(
        string $serialized,
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
            ['{"a":"a"}', SerializationType::JSON(), JsonContext::class]
        ];
    }
}

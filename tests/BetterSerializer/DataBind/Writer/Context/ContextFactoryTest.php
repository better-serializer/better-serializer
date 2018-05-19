<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\Writer\Context;

use BetterSerializer\Common\SerializationType;
use BetterSerializer\DataBind\Writer\Context\Json\Context as JsonContext;
use BetterSerializer\DataBind\Writer\Context\PhpArray\Context as PhpArrayContext;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class ContextFactoryTest extends TestCase
{

    /**
     * @dataProvider serializationTypeProvider
     * @param SerializationType $serializationType
     * @param string $contextClass
     * @throws RuntimeException
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testCreateContext(SerializationType $serializationType, string $contextClass): void
    {
        $factory = new ContextFactory();
        $context = $factory->createContext($serializationType);

        self::assertInstanceOf($contextClass, $context);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessageRegExp /Invalid serialization type: [a-zA-Z0-9_]+/
     */
    public function testCreateContextWithUnsupportedType(): void
    {
        $factory = new ContextFactory();
        $factory->createContext(SerializationType::NONE());
    }

    /**
     * @return array
     */
    public function serializationTypeProvider(): array
    {
        return [
            [SerializationType::JSON(), JsonContext::class],
            [SerializationType::PHP_ARRAY(), PhpArrayContext::class]
        ];
    }
}

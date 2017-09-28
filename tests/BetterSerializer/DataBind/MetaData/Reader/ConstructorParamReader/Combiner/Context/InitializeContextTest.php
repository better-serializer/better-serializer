<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context;

use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\ShrinkingPropertiesMetaDataInterface;
use BetterSerializer\Reflection\ReflectionMethodInterface;
use PHPUnit\Framework\TestCase;

/**
 * Class InitializeContextTest
 * @author mfris
 * @package BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context
 */
class InitializeContextTest extends TestCase
{

    /**
     *
     */
    public function testEverything(): void
    {
        $constructor = $this->createMock(ReflectionMethodInterface::class);
        $propertiesMetaData = $this->createMock(ShrinkingPropertiesMetaDataInterface::class);

        $context = new InitializeContext($constructor, $propertiesMetaData);

        self::assertSame($constructor, $context->getConstructor());
        self::assertSame($propertiesMetaData, $context->getPropertiesMetaData());
    }
}

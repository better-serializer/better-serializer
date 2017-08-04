<?php
declare(strict_types=1);

/**
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\Context;

use BetterSerializer\DataBind\MetaData\Reader\ConstructorParamReader\Combiner\ShrinkingPropertiesMetaDataInterface;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

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
        $constructor = $this->getMockBuilder(ReflectionMethod::class)
            ->disableOriginalConstructor()
            ->getMock();

        $propertiesMetaData = $this->getMockBuilder(ShrinkingPropertiesMetaDataInterface::class)->getMock();

        /* @var $constructor ReflectionMethod */
        /* @var $propertiesMetaData ShrinkingPropertiesMetaDataInterface */
        $context = new InitializeContext($constructor, $propertiesMetaData);

        self::assertSame($constructor, $context->getConstructor());
        self::assertSame($propertiesMetaData, $context->getPropertiesMetaData());
    }
}

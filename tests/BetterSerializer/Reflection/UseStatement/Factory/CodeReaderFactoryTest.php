<?php
declare(strict_types=1);

/*
 * @author Martin Fris <rasta@lj.sk>
 */

namespace BetterSerializer\Reflection\UseStatement\Factory;

use BetterSerializer\Reflection\UseStatement\CodeReader;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Class CodeReaderFactoryTest
 * @author mfris
 * @package BetterSerializer\Reflection\UseStatement\Factory
 */
class CodeReaderFactoryTest extends TestCase
{

    /**
     *
     */
    public function testNewCodeReader(): void
    {
        $reflectionClass = $this->getMockBuilder(ReflectionClass::class)
            ->disableOriginalConstructor()
            ->getMock();

        $reflectionClass->expects(self::once())
            ->method('getFileName')
            ->willReturn(__FILE__);

        $reflectionClass->expects(self::once())
            ->method('getStartLine')
            ->willReturn(3);

        /* @var $reflectionClass ReflectionClass */
        $factory = new CodeReaderFactory();
        $codeReader = $factory->newCodeReader($reflectionClass);

        self::assertInstanceOf(CodeReader::class, $codeReader);
    }
}
